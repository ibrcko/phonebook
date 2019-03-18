<?php

namespace App\Repository;

use App\Contact;
use App\PhoneNumber;

class PhoneNumberRepository extends Repository
{
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

    public function update($data, $phoneNumber)
    {
        $phoneNumber->update($data);
        $phoneNumber->save();

        return $phoneNumber;
    }

    public function delete($phoneNumber)
    {
        $phoneNumber->delete();

        return $phoneNumber;

    }
}
