{{--
    Proposal · Design 2 ("Locket / Heart Open") — theme 4, "Onyx & Silver".

    A thin wrapper, exactly like the anniversary gift views: the design itself
    lives once in the partial, and each of its four colour themes is one file
    that includes it with a `proposalTheme`. Served at
    /proposal/design/2/4 and rendered at /c/{slug} for a published card.
--}}
@include('birthday.partials.proposal-design-2', ['proposalTheme' => 4])
