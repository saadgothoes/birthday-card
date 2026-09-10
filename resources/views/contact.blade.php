<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Get in touch with BirthdayCard — message us on WhatsApp, Instagram or email about payments, plans or your card.">
    <title>Contact — BirthdayCard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600;1,700&family=DM+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>

<body class="landing">

    @include('landing._cursor')
    @include('landing._nav')

    <main>
        {{-- Page header — the section itself only renders the channel grid. --}}
        <section class="l-contact-hero">
            <div class="container">
                <span class="eyebrow">Get in touch</span>
                <h1>Questions?<br><em>Talk to a human.</em></h1>
                <p>Payments, plans, or a card that will not behave — message us on whichever of these you already
                    have open. We reply on all of them.</p>
            </div>
        </section>

        @include('landing._contact')

        @if ($supportContacts->isEmpty())
            <section class="l-contact">
                <div class="container">
                    <div class="l-contact__empty">
                        <p>Our contact channels are being updated right now. Please check back shortly — or sign in
                            and reach us from your dashboard.</p>
                        <a href="{{ route('client.login') }}" class="l-contact__empty-link">Client Login →</a>
                    </div>
                </div>
            </section>
        @endif

        {{-- What people actually write in about, so the common cases never need
             a message at all. --}}
        <section class="l-contact-faq">
            <div class="container">
                <div class="section-head">
                    <span class="eyebrow">Before you message</span>
                    <h2>Common <em>questions.</em></h2>
                </div>

                <div class="l-faq">
                    <div class="l-faq__item">
                        <h3>I paid but my plan is still locked.</h3>
                        <p>Approvals are manual. Once you submit your payment screenshot we verify the transfer and
                            switch your plan on — message us if it has been a while and nothing has changed.</p>
                    </div>
                    <div class="l-faq__item">
                        <h3>My transfer failed but the money was deducted.</h3>
                        <p>Send us the transaction ID and a screenshot. We will check it against our account and
                            either activate your plan or help you get it reversed.</p>
                    </div>
                    <div class="l-faq__item">
                        <h3>Which accounts do you accept payment on?</h3>
                        <p>Sign in and open a plan — the accounts are listed there. Those are the only accounts we
                            use. If someone gives you a different number, message us before sending anything.</p>
                    </div>
                    <div class="l-faq__item">
                        <h3>My card link or QR is not opening.</h3>
                        <p>Send us the link. Cards can be switched off from your own dashboard, so it may just need
                            turning back on.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('landing._footer')

</body>

</html>
