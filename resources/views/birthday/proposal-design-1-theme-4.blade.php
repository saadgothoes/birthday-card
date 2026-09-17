{{--
    Proposal · Design 1 ("The Last Message") — theme 4, "Matcha".

    A thin wrapper, exactly like the anniversary gift views: the design itself
    lives once in the partial, and each of its four colour themes is one file
    that includes it with a `proposalTheme`. Served at
    /proposal/design/1/4 and rendered at /c/{slug} for a published card.
--}}
@include('birthday.partials.proposal-design-1', ['proposalTheme' => 4])
