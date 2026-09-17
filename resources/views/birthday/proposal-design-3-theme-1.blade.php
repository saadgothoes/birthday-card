{{--
    Proposal · Design 3 ("Written in the Stars") — theme 1, "Deep Indigo".

    A thin wrapper, exactly like the anniversary gift views: the design itself
    lives once in the partial, and each of its four colour themes is one file
    that includes it with a `proposalTheme`. Served at
    /proposal/design/3/1 and rendered at /c/{slug} for a published card.
--}}
@include('birthday.partials.proposal-design-3', ['proposalTheme' => 1])
