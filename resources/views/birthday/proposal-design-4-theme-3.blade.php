{{--
    Proposal · Design 4 ("The Roll") — theme 3, "Darkroom".

    A thin wrapper, exactly like the anniversary gift views: the design itself
    lives once in the partial, and each of its four colour themes is one file
    that includes it with a `proposalTheme`. Served at
    /proposal/design/4/3 and rendered at /c/{slug} for a published card.
--}}
@include('birthday.partials.proposal-design-4', ['proposalTheme' => 3])
