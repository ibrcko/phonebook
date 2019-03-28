<?php
namespace App\Repository;

use App\Contact;
use App\PhoneNumber;

/**
 * Class PhoneNumberRepository
 * @package App\Repository
 * Class that provides access to the database table phone_numbers
 */
class PhoneNumberRepository extends Repository
{
    /**
     * @return PhoneNumber[]|\Illuminate\Database\Eloquent\Collection
     * Method that retreives all PhoneNumber records;
     */
    public function getAll()
    {
        $contacts = PhoneNumber::all();

        return $contacts;
    }
    /**
     * @param $data
     * @return mixed
     * Method that creates PhoneNumber record
     */
    public function create($data)
    {
        $phoneNumber = new PhoneNumber();

        $phoneNumber->number = $data['number'];
        $phoneNumber->name = $data['name'];
        $phoneNumber->label = $data['label'];

        $contact = Contact::find($data['contact_id']);

        $number = $contact->phoneNumbers()->save($phoneNumber);

        return $number;
    }

    /**
     * @param $data
     * @param $phoneNumber
     * @return mixed
     * Method that updates PhoneNumber record
     */
    public function update($data, $phoneNumber)
    {
        $phoneNumber->update($data);
        $phoneNumber->save();

        return $phoneNumber;
    }

    /**
     * @param $phoneNumber
     * @return mixed
     * Method that deletes PhoneNumber record
     */
    public function delete($phoneNumber)
    {
        $phoneNumber->delete();

        return $phoneNumber;

    }

    /**
     * @param $id
     * @return mixed
     * Method that reads a single PhoneNumber record
     */
    public function find($id)
    {
        $phoneNumber = PhoneNumber::find($id);

        return $phoneNumber;
    }
}
