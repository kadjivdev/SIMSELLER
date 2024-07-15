<div class="card-footer clearfix">
    <ul class="pagination pagination-sm m-0 float-right">
    @if ($paginator->hasPages())
        {{-- Bouton "Page précédente" --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Précédent</a></li>
        @endif

        {{-- Liste des numéros de page --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><a class="page-link" href="">{{ $element }}2</a></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        <a href=""></a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Bouton "Page suivante" --}}
        @if ($paginator->hasMorePages())
            <li class="page-item "><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant</a></li>
        @else
            <li class="page-item disabled"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Suivant</a></li>
        @endif
    @endif
    </ul>
</div>
