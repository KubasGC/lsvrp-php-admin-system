var shopsVue = new Vue({
    el: "#shops",
    data: {
        loaded: false,
        shopsData: null,
        itemTypes: null,

        newItemData: {}
    },
    methods: {
        DeleteProduct: function(productId, productName) {
            "use strict";

            swal({
                title: "Potwierdzenie",
                text: `Czy na pewno chcesz usunąć produkt \"${productName}\"?`,
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Usuń",
                        closeModal: false
                    }
                },
                dangerMode: true
            }).then(ok => {
                if (!ok) return;
                $.get(`https://admin.lsvrp.ga/ajax/shops/remove-product/${productId}`, {}, function(data) {
                    if (data !== null && data.success === true)
                    {
                        swal("Sukces", "Pomyślnie usunięto przedmiot z bazy danych.", {
                            icon: "success"
                        });
                        LoadShopsData();
                    }
                });
            });
        },
        AddProduct: function(shopId) {
            "use strict";
            if (this.newItemData[shopId].name === null || this.newItemData[shopId].type === null ||
                this.newItemData[shopId].val1 === null || this.newItemData[shopId].val2 === null ||
                this.newItemData[shopId].val3 === null || this.newItemData[shopId].price === null) {
                swal("Błąd", "Nie wypełniłeś wszystkich pól.", {
                    icon: "error"
                });
                return;
            }

            this.newItemData[shopId].adding = true;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("https://admin.lsvrp.ga/ajax/shops/add-product", {
                shopId: shopId,
                name: this.newItemData[shopId].name,
                type: this.newItemData[shopId].type,
                val1: this.newItemData[shopId].val1,
                val2: this.newItemData[shopId].val2,
                val3: this.newItemData[shopId].val3,
                price: this.newItemData[shopId].price
            }, function(data) {

                if (data.status !== true) return;

                LoadShopsData();
                shopsVue.newItemData[shopId].adding = false;
                shopsVue.newItemData[shopId].name = null;
                shopsVue.newItemData[shopId].type = null;
                shopsVue.newItemData[shopId].val1 = null;
                shopsVue.newItemData[shopId].val2 = null;
                shopsVue.newItemData[shopId].val3 = null;
                shopsVue.newItemData[shopId].price = null;
            });

        },
        ChangeHidden: function(shopId) {
            "use strict";
            $(`#sbody_${shopId}`).toggle();
        }
    }
});

function LoadShopsData() {
    "use strict";
    $.get("https://admin.lsvrp.ga/ajax/shops/getData", {}, function(data) {

        shopsVue.newItemData = {};

        for (let i = 0; i < data.shopsData.length; i++)
        {
            shopsVue.newItemData[data.shopsData[i].Id] = { name: null, type: null, val1: null, val2: null, val3: null, price: null, adding: false, hidden: true };
        }

        shopsVue.shopsData = data.shopsData;
        shopsVue.itemTypes = data.itemTypes;
        shopsVue.loaded = true;
    });
}

$(function() {
    "use strict";

    LoadShopsData();
});