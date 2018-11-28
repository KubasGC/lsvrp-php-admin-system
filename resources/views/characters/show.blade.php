@extends("base.page-base")
@section("title", "Zarządzanie postacią")

@section("content")
    <div id="char" class="container-fluid">
        <div class="block-header">
            <h2>{{ $charData->Name }} {{ $charData->Lastname }} (UID: {{ $charData->Id }})</h2>
        </div>
        <div class="card">
            <div class="header">
                <h2>
                    Edycja danych postaci
                    <small>Poniżej znajdują się dane postaci, które można dowolnie modyfikować.</small>
                </h2>
            </div>
            <div class="body">
                <div v-show="!loaded" class="lds-dual-ring"></div>
                <div v-show="loaded">
                    <strong>ID POSTACI</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="number" class="form-control" v-model="fCharId" disabled>
                        </div>
                    </div>

                    <strong>IMIĘ POSTACI</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" class="form-control" v-model="fName">
                        </div>
                    </div>

                    <strong>NAZWISKO POSTACI</strong>
                    <div class="input-group">
                        <div class="form-line">
                            <input type="text" class="form-control" v-model="fLastName">
                        </div>
                    </div>

                    <strong>PŁEĆ</strong>
                    <div class="input-group">
                        <input name="sex" type="radio" id="sex_male" value="1" v-model.number="fSex">
                        <label for="sex_male">Mężczyzna</label><br />
                        <input name="sex" type="radio" id="sex_female" value="2" v-model.number="fSex">
                        <label for="sex_female">Kobieta</label>
                    </div>

                    <strong>GOTÓWKA W PORTFELU</strong>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <div class="form-line">
                            <input type="number" class="form-control" v-model="fMoney">
                        </div>
                    </div>

                    <strong>CZAS ONLINE</strong>
                    <div class="input-group">
                        {{ floor($charData->OnlineTime / 3600) }}h
                    </div>

                    <strong>DNA</strong>
                    <div class="input-group">
                        {{ $charData->DNA }} ({{ $charData->ShortDNA }})
                    </div>

                    <strong>HP</strong>
                    <div class="input-group">
                        {{ floor($charData->Health) }}%
                    </div>

                    <strong>SKIN</strong>
                    <div class="input-group">
                        {{ $charData->SkinName }} (Domyślny skin: {{ $charData->DefaultSkinName }})
                    </div>

                    <strong>STATUS</strong>
                    <div v-if="charData !== null" class="input-group">
                        <span class="label label-success" v-if="charData.Blocked === 0">AKTYWNA</span>
                        <span class="label label-danger" v-if="charData.Blocked === 1">ZABLOKOWANA</span>
                        <span class="label bg-orange" v-if="charData.InGame === 1">W GRZE</span>
                        <a href="#!" style="display: block" @click="unblockChar" v-if="charData.Blocked === 1"> Odblokuj postać</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
              <h2>
                  Kary
                  <small>Poniżej możesz nadać kary użytkownikowi przez panel WWW.</small>
              </h2>
            </div>
            <div class="body">
                <button type="button" @click="kickPlayer" class="btn bg-teal waves-effect">KICK</button>
                <button type="button" @click="blockChar" class="btn bg-indigo waves-effect">BLOKADA POSTACI</button>
                <button type="button" @click="banPlayer" class="btn bg-red waves-effect">BAN</button>
            </div>
        </div>

        <div class="card">
            <div class="header">
                <h2>
                    Logi kar
                    <small>Poniżej możesz zobaczyć logi kar użytkownika.</small>
                </h2>
            </div>
            <div class="body table-responsive">
                <table class="table">
                    <thead>
                        <th>RODZAJ</th>
                        <th>POWÓD</th>
                        <th>DATA</th>
                    </thead>
                    <tbody v-if="penaltiesData !== null">
                        <tr v-if="penaltiesData.length === 0">
                            <td colspan="3" style="text-align: center;"><em>Postać nie posiada żadnych kar.</em></td>
                        </tr>
                        <tr v-for="penalty in penaltiesData">
                            <td>
                                @{{ penalty.TypeName }}<br>
                                <small>Nałożono przez: @{{ penalty.AdminName }}</small>
                            </td>
                            <td>
                                @{{ penalty.Reason }}<br>
                                <small v-if="penalty.Expired === 0">Nigdy nie wygasa</small>
                                <small v-else-if="penalty.Expired === -1"><em>Anulowana</em></small>
                                <small v-else>Wygasa: @{{ penalty.Ending }}</small>
                            </td>
                            <td>
                                @{{ penalty.Added }}
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="3" style="text-align: center;"><em>Postać nie posiada żadnych kar.</em></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section("js")
    <!-- Bootstrap Colorpicker Js -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="/js/ajax/char.js"></script>

    <script>
        $(function () {
            LoadCharData({{ $charData->Id }});
        });

    </script>
@endsection