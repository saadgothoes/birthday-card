{{--
    Proposal · Design 3 ("Countdown Reveal") — theme 2, "Deep Sea".

    A thin wrapper, exactly like the anniversary gift views: the design itself
    lives once in the partial, and each of its four colour themes is one file
    that includes it with a `proposalTheme`. Served at
    /proposal/design/3/2 and rendered at /c/{slug} for a published card.
--}}
@include('birthday.partials.proposal-design-3', ['proposalTheme' => 2])
