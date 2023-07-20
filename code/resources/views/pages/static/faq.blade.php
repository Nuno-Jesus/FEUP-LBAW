@extends('layouts.app')

@section('content')
    <section class="QA-Container">
        <div class="QA-1">
            <!-- faq question -->
            <h1 class="question">How can I create an account/sign-in into an existing account?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p> In order to register a new account, go to the <a href = "{{ route('login') }}">login page</a> and fill the form with your information.
                </p>
                <p> If you want to log into an existing account, go to the <a href = "{{ route('login') }}">login page</a> and enter your credentials.
            </div>
        </div>

        <hr>
        
        <div class="QA-2">
            <!-- faq question -->
            <h1 class="question">How do I add a product to my cart/wishlist?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p> You can go to the categories option, and select from which product category you want to buy from, then a list of products will be shown to you and all 
                    you have to do is click on the cart/heart icon. 
                </p>
                <p> You can also search directly for the product you want. If the search returns any product, you can add them to your cart/wishlist by tapping the cart/heart icon.
                </p>
            </div>
        </div>

        <hr>
        
        <div class="QA-3">
            <!-- faq question -->
            <h1 class="question">What is the purpose of notifications sidebar in my profile?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p>When an item that you have on your wishlist or cart, drops in price, you will receive a notification from us.
                </p>
                <p> You will also receive a notification from us when an out-of-stock item that you had on your wishlist becomes available.
                </p>
                <p>You can eliminate them by tapping the  marking as read button</p>
            </div>
        </div>

        <div class="QA-7">
            <!-- faq question -->
            <h1 class="question">How does my payment get approved?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p>After you make an order, we will check with the bank/paypal if your credentials match their records</p>
                <p>After that, you order will change status from <i>Processing</i> to <i>Accepted</i> and you will receive a notification.</p>
                <p>All your payment data is encrypted, and handled with the maximum security requirements by us. </p>
            </div>
        </div>

        <div class="QA-4">
            <!-- faq question -->
            <h1 class="question">Can I cancel my order?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p>Yes, you can cancel your order if it has not yet been accepted by the store.
                </p>
                <p>If an order can be cancelled, you will see a button for it on the Order History page of your account.</p>
            </div>
        </div>

        <div class="QA-5">
            <!-- faq question -->
            <h1 class="question">How long will it take my order to arrive</h1>
            <!-- faq answer -->
            <div class="answer">
                <p>We expect our orders to be processed and packed within 24 hours of their creation and delivered 24h-48h after being shipped. We ship our products internationally with UPS and DHL.
                </p>
                <p>You will receive a tracking number after we hand your package to the carrier</p>
                <p>Please keep in mind that after an order has been shipped, delivery dates are out of your control and should be concerned to the carrier in question</p>
            </div>
        </div>

        <div class="QA-6">
            <!-- faq question -->
            <h1 class="question">If I'm not happy with a product, can I write a review?</h1>
            <!-- faq answer -->
            <div class="answer">
                <p>Yes you can and we encourage you to. It helps our customers know whether a product is worth buying or not.</p>
                <p>To do so, just go over to the product page, choose a rating, and voice your opinion.</p>
                <p>Keep in mind that other users can report your review if they think it contains misinformation or they find it inadequate.</p>
                <p>You can delete your review if you think your opinion no longer applies.</p> 
            </div>
        </div>


    </section>
        
@endsection