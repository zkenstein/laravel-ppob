<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Customer;
use App\Admin;
use Session;
use Hash;

class AuthController extends Controller {

	public function adminLogin(Request $req){
    $this->validate($req,[
      'username' => 'required|min:4|max:8',
      'password' => 'required|min:4|max:8'
    ]);

    $user = Admin::where('username',$req->username)->first();
    if(count($user) > 0){
      if(Hash::check($req->password,$user->password)){
        $session = array(
          'id' => $user->id,
          'level' => $user->id_level,
          'login' => true,
          'name' => $user->name
        );
        Session::put($session);

        switch($user->id_level){
          case 1:
            return redirect('admin');
            break;
          case 2:
            return redirect('teller');
            break;
        }
      }else{
        return redirect()->back()->with('fail','Wrong password!')->withInput();
      }
    }else{
      return redirect()->back()->with('fail','Username not registered!')->withInput();
    }
  }

  public function customerLogin(Request $req){
    $this->validate($req,[
      'username' => 'required|min:4|max:8',
      'password' => 'required|min:4|max:8'
    ]);

    $customer = Customer::where('username', $req->username)->first();
    if(count($customer) > 0){
      if(Hash::check($req->password,$customer->password)){
        $session = array(
          'id' => $customer->id,
          'login' => true,
          'level' => 'customer',
          'name' => $customer->name
        );
        Session::put($session);
        
        return redirect('/');
      }else{
        return redirect()->back()->with('fail','Wrong password')->withInput();
      }
    }else{
      return redirect()->back()->with('fail','Username not registered!')->withInput();
    }
  }
}
