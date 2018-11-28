@extends("base.page-base")
@section("title", "Zarządzanie sklepami")

@section("content")
    <div id="shops" class="container-fluid">
        <div class="block-header">
            <h2>ZARZĄDZANIE SKLEPAMI</h2>
        </div>

        <div v-show="!loaded" class="lds-center">
            <div class="lds-dual-ring"></div>
        </div>

        <div v-show="loaded" v-for="shopData in shopsData" class="card">
            <div class="header">
                <h2>
                    @{{ shopData.Name }}
                    <small>UID: @{{ shopData.Id }}</small>
                </h2>
                <ul class="header-dropdown m-r-0">
                    <li>
                        <a href="javascript:void(0);" @click="ChangeHidden(shopData.Id)">
                            <i class="material-icons">view_comfy</i>
                        </a>
                    </li>
                </ul>
            </div>
            <form :id="'shop_' + shopData.Id">
                <div :id="'sbody_' + shopData.Id" class="body table-responsive" style="display: none;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">NAZWA</th>
                                <th style="width: 15%;">TYP</th>
                                <th style="width: 40%;">WARTOŚCI</th>
                                <th style="width: 10%;">CENA</th>
                                <th style="width: 10%;">OPCJE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in shopData.Products">
                                <td><input type="text" :name="'product_' + item.Id + '_name'" class="form-control" :value="item.Name"></td>
                                <td>@{{ item.TypeName }}</td>
                                <td>
                                    <input type="number" :name="'product_' + item.Id + '_val1'" class="form-control" style="width: 31%; display: inline-block;" :value="item.Value1">
                                    <input type="number" :name="'product_' + item.Id + '_val2'" class="form-control" style="width: 31%; display: inline-block;" :value="item.Value2">
                                    <input type="text" :name="'product_' + item.Id + '_val3'" class="form-control" style="width: 31%; display: inline-block;" :value="item.Value3">
                                </td>
                                <td><input type="number" :name="'product_' + item.Id + '_price'" class="form-control" :value="item.Price"></td>
                                <td><a href="#!" @click="DeleteProduct(item.Id, item.Name)"><ion-icon name="trash" style="font-size: 20px;"></ion-icon></a></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" v-model="newItemData[shopData.Id]['name']"></td>
                                <td>
                                    <select class="form-control" v-model.number="newItemData[shopData.Id]['type']">
                                        <option v-for="(value, key) in itemTypes" :value="key">@{{ value }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" v-model.number="newItemData[shopData.Id]['val1']" style="width: 31%; display: inline-block;">
                                    <input type="number" class="form-control" v-model.number="newItemData[shopData.Id]['val2']" style="width: 31%; display: inline-block;">
                                    <input type="text" class="form-control" v-model="newItemData[shopData.Id]['val3']" style="width: 31%; display: inline-block;">
                                </td>
                                <td><input type="number" class="form-control" v-model.number="newItemData[shopData.Id]['price']"></td>
                                <td>
                                    <a v-show="!newItemData[shopData.Id]['adding']" href="#!" @click="AddProduct(shopData.Id)"><ion-icon name="add-circle" style="font-size: 20px;"></ion-icon></a>
                                    <div v-show="newItemData[shopData.Id]['adding']" class="lds-dual-ring small"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary waves-effect">Zapisz zmiany</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section("js")
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="/js/ajax/shops.js"></script>
@endsection