<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
   public function index() {

     $search = request('search');

     if($search){

          $events = Event::where([
               ['title', 'like', '%'.$search.'%']
          ])->get();

     }else{
          
          $events = Event::all();
     }

        return view('welcome',['events' => $events, 'search'=> $search]);
   }

  
   public function create() {

        return view('events/create');

   }

   public function store(Request $request){

     $event= new Event;

     $event->title = $request->title;
     $event->date = $request->date;
     $event->city = $request->city;
     $event->private = $request->private;
     $event->description = $request->description;
     $event->items = $request->items;

     // IMAGE UPLOAD

     if($request->hasFile('image') && $request->file('image')->isValid()){

          $requestImage =  $request->image;

          $extension = $requestImage->extension();

          $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")). "." . $extension;

          $requestImage->move(public_path('img/events'), $imageName);

          $event->image = $imageName;
     }

     $user = auth()->user();

     $event->user_id = $user->id;
     $event->save(); 

     return redirect('/')->with('msg','Evento criado com sucesso!');

   }

   public function show($id){

     $user = auth()->user();

     // $events = $user->events;

     $eventAsParticipant = $user->eventsAsParticipant;

     
     
     $event = Event::findOrFail($id);
     
     $eventOwner = User::where('id', $event->user_id)->first()->toArray();
     

     $existeEvento = $eventAsParticipant->find($event->id);

    

     return view('events.show',['existeEvento'=>$existeEvento, 'event' =>$event, 'eventOwner'=>$eventOwner]);

   }

   public function dashboard(){


     $user = auth()->user();

     $events = $user->events;

     $eventAsParticipant = $user->eventsAsParticipant;

     // dd($eventAsParticipant);

    
     return view('events.dashboard',['events' =>$events, 'eventAsParticipant' => $eventAsParticipant]);

   }
   public function destroy($id)
   {
       Event::findOrFail($id)->delete();

       return redirect('/dashboard')->with('Evento deletado com sucesso!');
   }

   public function edit($id)
   {
        $event = Event::findOrFail($id);

        $user = auth()->user();

        if($user->id != $event->user_id){
          return redirect('/dashboard');
        }

        return view('events.edit', ['event'=>$event]);
   }

   public function update( Request $request)
   {
        $data = $request->all();

        if($request->hasFile('image') && $request->file('image')->isValid()){

          $requestImage =  $request->image;

          $extension = $requestImage->extension();

          $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")). "." . $extension;

          $requestImage->move(public_path('img/events'), $imageName);

          $data['image'] = $imageName;
     }

        Event::findOrFail($request->id)->update($data); 

        return redirect('/dashboard')->with('msg','Evento editado com sucesso!');
        
   }

   public function joinEvent($id)
   {
        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

      

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento ' . $event->title . ' dia ' . $event->date->format('d-m-Y'));
   }

   public function leaveEvent($id)
   {
        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu do evento ' . $event->title . ' Com Sucesso! ');


   }
   
}
