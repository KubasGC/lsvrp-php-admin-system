@extends("base.page-base")
@section("title", "Wyszukiwanie postaci")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>ZARZĄDZANIE POSTACIAMI</h2>
        </div>
        <div class="card">
            <div class="header">
                <h2>
                    Szukaj postaci
                    <small>Znajdź postać po jej danych</small>
                </h2>
            </div>
            <div class="body">
                <form id="char-search">
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="material-icons">search</i>
                    </span>
                        <div class="form-line">
                            <input id="searchChar" type="text" class="form-control date" placeholder="Wprowadź ID postaci, część jej nicku, nazwę lub ID konta globalnego. Możesz użyć także kodu DNA postaci.">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect" onclick="onSearchClicked(this)">SZUKAJ</button>
                </form>
            </div>
        </div>
        <br />
        <div id="searchLoader" style="text-align: center; display: none;">
            <div class="lds-dual-ring"></div>
        </div>

        <div id="searchData" class="card" style="display: none;">
            <div class="header">
                <h2>
                    Szukaj postaci
                    <small>Znajdź postać po jej danych</small>
                </h2>
            </div>
            <div class="body table-responsive">

            </div>
        </div>
    </div>
@endsection

@section("js")
    <script src="/js/ajax/characters.js"></script>
    <script>
        $("#char-search").submit(function(e) {
           e.preventDefault();
        });
        function onSearchClicked(button)
        {
            if ($("#searchChar").val().length < 1) return;
            $(button).attr("disabled", "true");
            $("#searchData").hide();
            $("#searchLoader").show();
            searchCharacter("{{ \Illuminate\Support\Facades\URL::route("ajax.searchChar") }}", "{{ csrf_token() }}",
                $("#searchChar").val().toString(), button);
        }
    </script>
@endsection