<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Advert;

class UserController extends Controller
{
    public function index() {

        $search = request('search');

        if($search) {

            $adverts = Advert::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        } else {
            $adverts = Advert::all(); // puxanto todos os dados
        }

        return view('index',['adverts' => $adverts, 'search' => $search]);

    }

    public function announce() {
        return view('events.announce');
    }

    public function store(Request $request) {

        $advert = new Advert;

        $advert->title = $request->title;
        $advert->date = $request->date; 
        $advert->preco = $request->preco;
        $advert->category = $request->category;
        $advert->description = $request->description;

        // image
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            
            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $request->image->move(public_path('img/announcement'), $imageName);

            $advert->image = $imageName;

        }

        /* salvando dados */
        $advert->save();

        return redirect('/');

    }

    // show
    public function show($id) {

        $advert = Advert::findOrFail($id);

        return view('events.show', ['advert' => $advert]);

    }

    public function profile() {
        return view('profile');
    }
}
