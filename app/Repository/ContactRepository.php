<?php

namespace App\Repository;


use App\Contact;
use App\PhoneNumber;
use App\User;
use Illuminate\Support\Facades\Storage;

class ContactRepository extends Repository
{
    public function getAll()
    {
        $contacts = Contact::with('phoneNumbers')
                ->limit(20)
                ->paginate(15);

        return $contacts;
    }

    public function create($data)
    {
        $contact = new Contact();

        $contact->first_name = $data['first_name'];
        $contact->last_name = $data['last_name'];
        $contact->email = $data['email'];
        $contact->favourite = $data['favourite'];

        $user = User::find($data['user_id']);

        $contact = $user->contacts()->save($contact);

        if (!empty($data['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($data['profile_photo']);

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $contact;
    }

    public function update($data, $contact)
    {
        $contact->update($data);
        $contact->save();

        return $contact;
    }

    public function find($id)
    {
        $contact = Contact::with('phoneNumbers')
            ->find($id);

        return $contact;
    }

    public function delete($contact)
    {
        if (!is_null($contact->profile_photo)) {
            $this->deleteProfilePhoto($contact);
        }

        $contact->delete();

        return $contact;
    }

    private function imageUpload($photo)
    {
        $name = time() . '.' . $photo->getClientOriginalExtension();

        $path = $photo->storeAs('profile-photos', $name);

        return $path;
    }

    public function updateImage($data, Contact $contact)
    {
        if (!empty($data['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($data['profile_photo']);

            $this->deleteProfilePhoto($contact);

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $contact;
    }

    private function deleteProfilePhoto($contact)
    {
        $path = $contact->profile_photo;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

}
