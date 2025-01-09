<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\DataLayer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\ContactInfo;




class ProfileController extends Controller
{
    
    public function edit()
    {   
        $user = Auth::user();

        // Verifica se l'utente ha già le contactInfo, se non le ha, inizializzale
        if (!$user->contactInfo) {
            $user->contactInfo()->create([
                'phone_number' => '',
                'address' => '',
                'zipcode' => '',
                'date_of_birth' => '2025-01-01',
            ]);
            $user->refresh(); // Ricarica l'utente per ottenere le nuove contactInfo
        }
        
        $contactInfo = $user->contactInfo;
        $dl = new DataLayer();
        $categories = $dl->listCategories();


        return view('user.edit', compact( 'user', 'contactInfo', 'categories'));
    }

    

    public function update(Request $request)
    {
        // Recupera l'utente autenticato
        $user = Auth::user();

        // Validazione dei dati
        // $validator = Validator::make($request->all(), [
        //     'firstname' => 'required|string|max:255',
        //     'lastname' => 'required|string|max:255',
        //     'address' => 'required|string|max:255',
        //     'zipcode' => 'required|string|max:10',
        //     'phone_number' => 'nullable|string|max:20',
        //     'email' => 'required|string|email|max:255|unique:user,email,' . $user->id,
        //     'password' => 'nullable|string|min:8|confirmed',
        //     'date_of_birth' => 'nullable|date',
        // ]);

        // // Controlla se la validazione fallisce
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput(); 
        // }

        // Aggiorna le informazioni dell'utente
        $contactInfo = $user->contactInfo();
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');

        // Aggiorna le informazioni di contatto
        $contactInfo = $user->contactInfo;
        if (!$contactInfo) {
            $contactInfo = new ContactInfo();
            $contactInfo->user_id = $user->id;
        }
        $contactInfo->address = $request->input('address');
        $contactInfo->zipcode = $request->input('zipcode');
        $contactInfo->date_of_birth = $request->input('date_of_birth');
        $contactInfo->phone_number = $request->input('phone_number');
        $contactInfo->save();

        // Aggiorna la password se è stata fornita
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Salva le modifiche
        $user->save();

        // Reindirizza indietro con un messaggio di successo
        return redirect()->back()->with('success', 'Profilo aggiornato con successo!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function profile() {
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        $contactInfo = Auth::user()->contactInfo;

        if(Auth::user()->isAdmin()){
            return redirect()->route('admin.home');
        }
        return view('user.profile')
            ->with('categories', $categories)
            ->with('contactInfo', $contactInfo);
    }

    public function orders(){
        $dl = new Datalayer;
        $userId = Auth::user()->id; 
        $orders =  $dl->userOrders($userId);
        $categories = $dl->listCategories();
        return view('user.orders')->with('categories', $categories)
                                  ->with('orders', $orders);
    }

    public function settings() {
        $dl = new DataLayer;
        $categories = $dl->listCategories();
        $user = Auth::user();

        return view('user.settings')->with('categories', $categories)
                                    ->with('user', $user);
    }

    public function wishlist() {
        $dl = new DataLayer;
        $userId = Auth::user()->id; 
        $wishlist =  $dl->userWishlist($userId);
        if (!$wishlist) { 
            $wishlist = $dl->createUserWishlist($userId); 
        }
        $categories = $dl->listCategories();
        return view('user.wishlist')->with('categories', $categories)
                                    ->with('wishlist', $wishlist);
    }

    public function addToWishlist(Request $request) {
        Log::info('addToWishlist Request Data:', $request->all()); // Log all request data
        $productId = $request->input('product_id');
        $dl = new DataLayer;
        $userId = Auth::user()->id; 

        if ($dl->addToWishlist($userId, $productId)) {
            return response()->json(['success' => true, 'message' => 'Product added to wishlist!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to add product to wishlist.'], 500); 
        }
    }

    public function removeFromWishlist($productId) {
        $dl = new DataLayer;
        $userId = Auth::user()->id;

        // Assuming your DataLayer has a method to remove from wishlist
        if ($dl->removeFromWishlist($userId, $productId)) {
            return back()->with('success', 'Product removed from wishlist!');
        } else {
            return back()->with('error', 'Failed to remove product from wishlist.');
        }
    }

    
}
