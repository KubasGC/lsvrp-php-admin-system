@extends("base.page-base")
@section("title") Grupa {{ $groupData->Name }} @endsection

@section("content")
    <div class="container-fluid">
        <div class="block-header">
            <h2>{{ $groupData->Name }} (UID: {{ $groupData->Id }})</h2>
        </div>

        {{-- Informacje o grupie --}}
        <div class="card">
            <div class="header">
                <h2>
                    Informacje o grupie
                    <small>Poniżej znajdziesz wszystkie podstawowe informacje o grupie.</small>
                </h2>
            </div>
            <div class="body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Wystąpiły następujące błędy:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" action="{{ \Illuminate\Support\Facades\URL::route("groups.savedata", ["groupid" => $groupData->Id]) }}">
                    @csrf
                    <strong>ID GRUPY</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="number" id="groupId" class="form-control" value="{{ $groupData->Id }}" disabled>
                        </div>
                    </div>

                    <strong>NAZWA GRUPY</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" name="groupName" class="form-control" value="{{ $groupData->Name }}">
                        </div>
                    </div>

                    <strong>TAG GRUPY</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" name="groupTag" class="form-control" value="{{ $groupData->Code }}">
                        </div>
                    </div>

                    <strong>TYP GRUPY</strong>
                    <div class="input-group">
                        <select name="groupType" class="selectpicker">
                            @foreach ($groupTypes as $key => $value)
                                <option value="{{ $key }}" @if($key == $groupData->Type) selected="true" @endif>({{ $key }}) {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <strong>KOLOR GRUPY</strong>
                    <div class="input-group colorpicker colorpicker-element" style="z-index: 0 !important;">
                        <div class="form-line">
                            <input id="cp-input" name="groupColor" type="text" class="form-control" value="{{ $groupData->HexColor }}">
                        </div>
                        <span class="input-group-addon"><i></i></span>
                    </div>



                    <strong>SPAWN GRUPY</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" class="form-control" value="Vector3({{ round($groupData->SpawnX, 2) }}, {{ round($groupData->SpawnY, 2) }}, {{ round($groupData->SpawnZ, 2) }})" disabled>
                        </div>
                    </div>

                    <strong>KONTO BANKOWE GRUPY</strong>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <div class="form-line">
                            <input type="text" name="groupBank" class="form-control date" value="{{ $groupData->Cash }}">
                        </div>
                    </div>

                    <strong>DOTACJA GRUPY</strong>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <div class="form-line">
                            <input type="text" name="groupDonation" class="form-control date" value="{{ $groupData->Donation }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary waves-effect">ZAPISZ ZMIANY</button>
                </form>
            </div>
        </div>

        {{-- Uprawnienia grupy --}}
        <div class="card">
            <div class="header">
                <h2>
                    Uprawnienia grupy
                    <small>Poniżej znajdziesz uprawnienia, jakie można nadać grupie.</small>
                </h2>
            </div>
            <div class="body">
                <form method="post" action="{{ \Illuminate\Support\Facades\URL::route("groups.saveperms", ["groupid" => $groupData->Id]) }}">
                    @csrf
                    @foreach($groupPermissions as $key => $value)
                        <input type="checkbox" value="true" id="perm_{{ $key }}" name="perm_{{ $key }}" @if($value["status"] == 1) checked @endif>
                        <label for="perm_{{ $key }}">{{ $value['desc'] }}</label>
                        <br />
                    @endforeach

                    <button type="submit" class="btn btn-primary waves-effect">ZAPISZ UPRAWNIENIA</button>
                </form>

            </div>
        </div>

        {{-- Zamówienia grupy --}}
        <div id="orders" class="card">
            <div class="header">
                <h2>
                    Zamówienia grupy
                    <small>Poniżej znajdziesz zamówienia do jakich ma dostęp grupa.</small>
                </h2>
            </div>
            <div class="body table-responsive">
                <form id="orders-edit" method="POST">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 20%;">NAZWA</th>
                                <th style="width: 20%;">TYP</th>
                                <th style="width: 30%;">WARTOŚCI</th>
                                <th style="width: 10%;">CENA</th>
                                <th style="width: 10%;">FLAGA</th>
                                <th style="width: 10%">SKASUJ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-if="orders === null || orders.length === 0">
                            <td colspan="6" style="text-align: center;"><em>Grupa nie posiada żadnych zamówień.</em></td>
                        </tr>
                        <tr v-if="orders !== null" v-for="order in orders">
                            <td><input type="text" :name="'Name_' + order.Id" :value="order.Name" class="form-control" title="Nazwa zamówienia"></td>
                            <td>@{{ order.TypeName }}</td>
                            <td>
                                <input type="text" :name="'Val1_' + order.Id" :value="order.Value1" class="form-control" style="width: 30%; display:inline-block;">
                                <input type="text" :name="'Val2_' + order.Id" :value="order.Value2" class="form-control" style="width: 30%; display:inline-block;">
                                <input type="text" :name="'Val3_' + order.Id" :value="order.Value3" class="form-control" style="width: 30%; display:inline-block;">
                            </td>
                            <td><input type="number" :name="'Price_' + order.Id" :value="order.Price" class="form-control"></td>
                            <td>
                                <div class="switch">
                                    <label><input type="checkbox" :name="'Flag_' + order.Id" v-model="order.Flag"><span class="lever"></span></label>
                                </div>
                            </td>
                            <td><a href="#!" @click="onDeleteClick(order.Id)"><i class="material-icons">remove_circle</i></a></td>
                        </tr>
                        </tbody>
                    </table>
                    <button v-show="!ordersButtonBlocked" type="button" @click="onSaveClick" class="btn btn-primary waves-effect">ZAPISZ ZMIANY</button>
                    <div v-show="ordersButtonBlocked" class="lds-dual-ring"></div>
                </form><br />
            </div>
            <div class="header">
                <h2>
                    Dodawanie nowego zamówienia
                    <small>Poniżej możesz dodać nowe zamówienie do grupy.</small>
                </h2>
            </div>
            <div class="body">
                <strong>NAZWA PRZEDMIOTU</strong>
                <div class="input-group">
                    <div class="form-line">
                        <input type="text" v-model="itemName" class="form-control">
                    </div>
                </div>

                <strong>CENA</strong>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <div class="form-line">
                        <input type="number" v-model.number="itemPrice" class="form-control date">
                    </div>
                </div>

                <strong>TYP PRZEDMIOTU</strong>
                <div class="input-group">
                    <select class="form-control" v-model.number="itemType">
                        @foreach($itemTypes as $typeKey => $typeValue)
                            <option value="{{ $typeKey }}">{{ $typeValue }}</option>
                        @endforeach
                    </select>
                </div>

                <strong>Wartość I</strong>
                <div class="input-group">
                    <div class="form-line">
                        <input type="number" v-model.number="itemVal1" class="form-control">
                    </div>
                </div>

                <strong>Wartość II</strong>
                <div class="input-group">
                    <div class="form-line">
                        <input type="number" v-model.number="itemVal2" class="form-control">
                    </div>
                </div>

                <strong>Wartość III</strong>
                <div class="input-group">
                    <div class="form-line">
                        <input type="text" v-model="itemVal3" class="form-control">
                    </div>
                </div>

                <strong>Flaga</strong>
                <div class="input-group">
                    <div class="switch">
                        <label><input type="checkbox" v-model="itemFlag"><span class="lever"></span></label>
                    </div>
                </div>

                <button type="button" @click="onSubmitClick" class="btn btn-success waves-effect">DODAJ ZAMÓWIENIE</button>
            </div>
        </div>

        {{-- Członkowie grupy --}}
        <div class="card">
            <div class="header">
                <h2>
                    Członkowie grupy
                    <small>Poniżej znajdziesz wszystkich członków grupy.</small>
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>GRACZ</th>
                            <th>RANGA</th>
                            <TH>DOŁĄCZYŁ</TH>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupMembers as $groupMember)
                            <tr>
                                <td>
                                    {{ $groupMember->Name }} {{ $groupMember->Lastname }}
                                    @if ($groupData->SuperLeaderID == $groupMember->CharID)
                                        <span class="badge bg-red">Właściciel</span>
                                    @endif
                                    <br /><small style="color: #ccc;"><a href="https://lsvrp.pl/profile/{{ $groupMember->MemberID }}-{{ $groupMember->Globalname }}/">
                                        {{ $groupMember->Globalname }}
                                        </a></small>
                                </td>
                                <td>
                                    {{ $groupMember->Rankname }}
                                    @if(in_array("leader", $groupMember->DecodedRankPermissions))
                                        <br /><span class="badge bg-deep-orange">Uprawnienia lidera</span>
                                    @endif
                                    @if($groupMember->RankID == $groupData->DefaultRank)
                                        <br /><span class="badge">Domyślna ranga</span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Jenssegers\Date\Date::createFromTimestamp($groupMember->CreatedAt)->diffForHumans() }}<br />
                                    <small style="color: #ccc">{{ \Jenssegers\Date\Date::createFromTimestamp($groupMember->CreatedAt)->format("l, j F Y H:i") }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("css")
    <!-- Colorpicker Css -->
    <link href="/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
   {{-- <link href="/plugins/sweetalert/sweetalert.css" rel="stylesheet" /> --}}
@endsection

@section("js")
    <!-- Bootstrap Colorpicker Js -->
    <script src="/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="/js/ajax/groups.js"></script>

    <script>
        $(function () {
            $(".colorpicker").colorpicker({
                format: "hex"
            });

            GroupLoadOrders({{ $groupData->Id }});
        });

    </script>
@endsection