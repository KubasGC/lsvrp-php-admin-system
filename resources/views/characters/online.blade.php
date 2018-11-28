@extends("base.page-base")
@section("title", "Gracze na serwerze")

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>GRACZE NA SERWERZE</h2>
        </div>
        <div class="card">
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>INFORMACJE O POSTACI</th>
                        <th>ONLINE OD</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($online as $char)
                        <tr>
                            <td>
                                <strong>(UID: {{ $char->CharId }}) {{ $char->Name }} {{ $char->Lastname }}</strong><br />
                                <small style="color: #999;">{{ $char->GlobalName }}</small>
                            </td>
                            <td>
                                {{ $char->SumTime }}<br>
                                <small style="color: #999;">{{ $char->LastLogged }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align: center;"><em>Nie ma nikogo na serwerze.</em></td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
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