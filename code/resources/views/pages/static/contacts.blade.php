@extends('layouts.app')

@section('content')
    <div class="contacts-row">
        <div class="contacts-column">
            <div class="contacts-card">    
                <h2 class="contacts-heading">Contact form</h2>
                <div class="form text-start">
                    <form action="#">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="firstname" placeholder="Your name..">

                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lastname" placeholder="Your last name..">

                        <label for="lname">Email Address</label>
                        <input type="text" id="email" name="email" placeholder="Your Email Address..">

                        <label for="subject">Subject</label>
                        <textarea id="subject" name="subject" placeholder="Write something.." style="height:100px"></textarea>
                        <input type="submit" value="Submit>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="contacts-column">
            <div class="contacts-card"> 
                <h2 class="contacts-heading"> Where we're located at</h2>
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1894.472395791228!2d-8.596225921265447!3d41.17810868120167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd246446d48922a3%3A0x8b1e4a0bcdacc840!2sFEUP%20-%20Faculdade%20de%20Engenharia%20da%20Universidade%20do%20Porto!5e1!3m2!1spt-PT!2spt!4v1641778891426!5m2!1spt-PT!2spt" width="600" height="482"  allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>                   

    @endsection