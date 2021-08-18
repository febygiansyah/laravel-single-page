<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;

class frontController extends Controller
{
    public function __construct(){

    }

    public function index(){
      $setups = DB::table('setups')->first();
      if(!empty($setups)){
        $socials = explode(',',$setups->social);
        $icons = explode(',',$setups->social);
        foreach ($icons as $icon) {
          $icon = explode('.',$icon);
          $icon = $icon[1];
          $fa[] = $icon;
        }
      }else{
        $socials = [];
      }
      $cats = DB::table('categories')->where('status','on')->get();
      $home = DB::table('contents')->where('category','home')->first();
      $about = DB::table('contents')->where('category','about us')->first();
      $about->slug = DB::table('categories')->where('title','about us')->value('slug');
      $services = DB::table('services')->where('status','on')->get();
      $services->slug = DB::table('categories')->where('title','services')->value('slug');
      $portfolio = DB::table('portfolios')->where('status','on')->get();
      foreach ($portfolio as $port) {
        $port->class = DB::table('portcats')->where('title',$port->category)->value('slug');
      }
      $portfolio->slug = DB::table('categories')->where('title','portfolio')->value('slug');
      $portcats = DB::table('portcats')->where('status','on')->get();
      $clients = DB::table('clients')->where('status','on')->get();
      $clients->slug = DB::table('categories')->where('title','clients')->value('slug');
      $teams = DB::table('teams')->where('status','on')->get();
      foreach ($teams as $team) {
        $team->teamurl = explode(',', $team->social);
        foreach ($team->teamurl as $url) {
          $icon = explode('.', $url);
          $icon = $icon[1];
          $team->font[] = $icon;
        }
      }
      $teams->slug = DB::table('categories')->where('title','teams')->value('slug');
      return view('index', [
        'setups' => $setups,
        'socials' => $socials,
        'fa' => $fa,
        'cats' => $cats,
        'home' => $home,
        'about' => $about,
        'services' => $services,
        'portfolio' => $portfolio,
        'portcats' => $portcats,
        'clients' => $clients,
        'teams' => $teams,
      ]);
    }

    public function sendMessage(){
      sleep(2);
      $data = $_GET;
      $data['created_at'] = date('Y-m-d H:i:s');
      DB::table('contacts')->insert($data);

      $output = 'Thank you for your message. We will contact you shortly';
      return response($output);
    }
}
