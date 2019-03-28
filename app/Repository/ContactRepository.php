<?php
namespace App\Repository;

use App\Contact;
use App\User;
use Illuminate\Support\Facades\Storage;

/**
 * Class ContactRepository
 * @package App\Repository
 * Class that provides access to the database table contacts
 */
class ContactRepository extends Repository
{
    /**
     * @param $userId
     * @return mixed
     * Method that reads all Contact records for a current user
     */
    public function getAll($userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->paginate(14);

        return $contacts;
    }

    /**
     * @param $userId
     * @return mixed
     * Method that reads all favourite Contact records for a current user
     */
    public function getAllFavourites($userId)
    {
        $contacts = Contact::where('user_id', $userId)
            ->where('favourite', true)
            ->paginate(14);

        return $contacts;
    }

    /**
     * @param $keyword
     * @param $userId
     * @return mixed
     * Method that searches all Contact records for a current user
     * It uses input query to filter through first_name and last_name values
     */
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

    /**
     * @param $keyword
     * @param $userId
     * @return mixed
     * Method that searches all favourite Contact records for a current user
     * It uses input query to filter through first_name and last_name values
     */
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

    /**
     * @param $data
     * @return Contact
     * Method that creates Contact record for a current user
     * Checks if profile_photo has been uploaded, and if so, dispatches it to the imageUpload method and saves the path value
     */
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

    /**
     * @param $photo
     * @return mixed
     * Method that creates a unique image name with current time and image extension
     * Stores the image in storage/app/public/profile-photos folder
     * Returns path of the stored image
     */
    private function imageUpload($photo)
    {
        $name = time() . '.' . $photo->getClientOriginalExtension();

        $path = $photo->storeAs('profile-photos', $name);

        return $path;
    }

    /**
     * @param $data
     * @param $contact
     * @return mixed
     * Method that updates Contact record
     * Dispatches uploaded image to updateImage method
     */
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

    /**
     * @param $data
     * @param Contact $contact
     * @return Contact
     * Method that checks if profile_photo has been uploaded, and if so, dispatches it to the imageUpload method
     * Dispatches Contact record to the deleteProfilePhoto method
     * Updates Contact record with the new image path
     */
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

    /**
     * @param $contact
     * Method that deletes old image from the file system
     */
    private function deleteProfilePhoto($contact)
    {
        $path = $contact->profile_photo;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    /**
     * @param $id
     * @return Contact|Contact[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * Method that reads a single Contact record and its related PhoneNumber records
     */
    public function find($id)
    {
        $contact = Contact::with('phoneNumbers')
            ->find($id);

        return $contact;
    }

    /**
     * @param $contact
     * @return mixed
     * Method that deletes a Contact record
     */
    public function delete($contact)
    {
        if (!is_null($contact->profile_photo)) {
            $this->deleteProfilePhoto($contact);
        }

        $contact->delete();

        return $contact;
    }

}
