<?php

namespace App\Repository;

use App\Contact;
use App\User;
use Illuminate\Support\Facades\Storage;

class ContactRepository extends Repository
{
    public function getAll($userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->paginate(14);

        return $contacts;
    }

    public function getAllFavourites($userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->where('favourite', true)
            ->paginate(14);

        return $contacts;
    }

    public function search($keyword, $userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%");
            })
            ->paginate(14);

        return $contacts;
    }

    public function searchFavourites($keyword, $userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->where('favourite', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%");
            })
            ->paginate(14);

        return $contacts;
    }

    public function create($data)
    {
        $contact = new Contact();

        $contact->first_name = $data['first_name'];
        $contact->last_name = $data['last_name'];
        $contact->email = $data['email'];
        if (!empty($data['favourite'])) {
            $contact->favourite = $data['favourite'];
        }

        if (!array_key_exists('user_id', $data)) {
            $userId = auth()->user()->getAuthIdentifier();
        } else {
            $userId = $data['user_id'];
        }

        $user = User::find($userId);

        $contact = $user->contacts()->save($contact);

        if (!empty($data['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($data['profile_photo']);

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $contact;
    }

    private function imageUpload($photo)
    {
        $name = time() . '.' . $photo->getClientOriginalExtension();

        $path = $photo->storeAs('profile-photos', $name);

        return $path;
    }

    public function update($data, $contact)
    {
        $this->updateImage($data, $contact);

        if (!empty($data['first_name']))
            $contact->first_name = $data['first_name'];
        if (!empty($data['last_name']))
            $contact->last_name = $data['last_name'];
        if (!empty($data['email']))
            $contact->email = $data['email'];

        $contact->favourite = $data['favourite'];

        $contact->save();

        return $contact;
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

}
