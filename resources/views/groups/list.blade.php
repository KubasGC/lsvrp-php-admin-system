@extends("base.page-base")
@section("title", "Lista grup")

@section("content")
<div class="container-fluid">
	<div class="block-header">
		<h2>LISTA GRUP</h2>
	</div>
	<div class="card">
        <div class="header">
			<h2>
				Grupy aktywne na serwerze
				<small>Poniżej znajdziesz wszystkie grupy na serwerze.</small>
			</h2>
        </div>
        <div class="body table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="ctcolumn">ID</th>
						<th class="ctcolumn">NAZWA [TAG]</th>
						<th class="ctcolumn">TYP</th>
						<th class="ctcolumn">ILOŚĆ CZŁONKÓW</th>
						<th class="ctcolumn">DOTACJA</th>
						<th class="ctcolumn">BANK</th>
						<th class="ctcolumn">EDYCJA</th>
					</tr>
				</thead>
				<tbody>
				@foreach($groupsData as $groupData)
				<tr>
					<td class="ctcolumn">{{ $groupData->Id }}</td>
					<td class="ctcolumn">{{ $groupData->Name }} <span style="color: rgb({{ $groupData->ColorR }}, {{ $groupData->ColorG }}, {{ $groupData->ColorB }}); text-shadow: 2px 2px 8px #000000;">[{{ $groupData->Code }}]</span></td>
					<td class="ctcolumn">{{ $groupData->TypeName }}</td>
					<td class="ctcolumn">{{ $groupData->employees }}</td>
					<td class="ctcolumn">${{ number_format($groupData->Donation) }}</td>
					<td class="ctcolumn">${{ number_format($groupData->Cash) }}</td>
					<td class="ctcolumn settings-column"><a href="{{ \Illuminate\Support\Facades\URL::route("groups", ["groupid" => $groupData->Id])  }}"><i class="material-icons">remove_red_eye</i></a></td>
				</tr>
				@endforeach
				</tbody>
			</table>
        </div>
    </div>
</div>
@endsection