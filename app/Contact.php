<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $table = 'contacts';
    protected $fillable = ['name', 'title', 'email', 'telephone', 'address'];



    public static function createContact($data){
        $contact = new Contact(array(
            'name' => $data['name'],
            'title' => $data['title'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'address' => $data['address']
        ));

        $contact->user()->associate(User::find($data['id']));

        $contact->save();

        return $newContact= Contact::where('id', $contact->id)
            ->get();
    }

    public static function showContact(){
        return Contact::orderBy('created_at')
        ->limit(2)->get();
    }

    public static function updateContact($update, $id){
        $contact = Contact::find($id);

        $contact->fill($update);

        $contact->save();

        return $contact;
    }

    public static function deleteContact($id){
        $material = Contact::find($id);

        return $material->delete();
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
