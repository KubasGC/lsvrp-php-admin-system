var groupShowVue = new Vue({
    el: '#orders',
    data: {
        itemName: null,
        itemPrice: null,
        itemType: null,
        itemVal1: null,
        itemVal2: null,
        itemVal3: null,
        itemFlag: false,

        orders: null,
        ordersButtonBlocked: false
    },
    methods: {
        onSubmitClick: function() {

            if (this.itemName === null || this.itemPrice === null || this.itemType === null || this.itemVal1 === null
            || this.itemVal2 === null || this.itemVal3 === null)
            {
                swal({
                    title: "Wystąpił błąd!",
                    text: `Nie wypełniłeś wszystkich potrzebnych pól.`,
                    icon: "error",
                    button: true,
                });
                return;
            }

            if (this.itemPrice < 1)
            {
                swal({
                    title: "Wystąpił błąd!",
                    text: `Kwota musi być dodatnia.`,
                    icon: "error",
                    button: true,
                });
                return;
            }

            swal("Zapisywanie zmian...", { buttons: false });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("https://admin.lsvrp.ga/ajax/group/add-order", {
                groupId: parseInt($("#groupId").val()),
                name: this.itemName,
                price: this.itemPrice,
                type: this.itemType,
                val1: this.itemVal1,
                val2: this.itemVal2,
                val3: this.itemVal3,
                flag: this.itemFlag === true ? 1 : 0
            },
                function(data) {
                "use strict";
                if (data.status === true)
                {
                    swal({
                        title: "Akcja wykonana pomyślnie!",
                        text: `Dodałeś zamówienie o nazwie \"${groupShowVue.itemName}\" dla grupy.`,
                        icon: "success",
                        button: true,
                    });



                    groupShowVue.itemName = null;
                    groupShowVue.itemPrice = null;
                    groupShowVue.itemType = null;
                    groupShowVue.itemType = null;
                    groupShowVue.itemVal1 = null;
                    groupShowVue.itemVal2 = null;
                    groupShowVue.itemVal3 = null;
                    groupShowVue.itemFlag = false;

                    GroupLoadOrders(parseInt($("#groupId").val()));
                }
                else
                {
                    swal({
                        title: "Wystąpił błąd!",
                        text: `Dodawanie nie powiodło się.`,
                        icon: "error",
                        button: true,
                    });
                }
            });
        },
        onDeleteClick: function(orderId) {
            "use strict";
            swal({
                title: "Potwierdzenie usunięcia",
                text: `Czy usunąć zamówienie o ID ${orderId}?`,
                icon: "warning",
                buttons: [true, {text: "Usuń", closeModal:false}],
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.get(`https://admin.lsvrp.ga/ajax/group/delete-order/${orderId}`, function(data) {
                            if (data.status === true) {
                                GroupLoadOrders(parseInt($("#groupId").val()));
                                swal("Zamówienie zostało usunięte pomyślnie.", {
                                    icon: "success"
                                });
                            }
                            else {
                                swal("Wystąpił nieznany błąd.", {
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
        },
        onSaveClick: function() {
            "use strict";
            this.ordersButtonBlocked = true;
            $.post(`https://admin.lsvrp.ga/ajax/group/save-orders/${parseInt($("#groupId").val())}`, $("#orders-edit").serialize(), function(data) {
                if (data.status === true)
                {
                    swal("Sukces", "Pomyślnie zapisano dane zamówień grupy.", {
                        icon: "success"
                    });
                    groupShowVue.ordersButtonBlocked = false;
                    GroupLoadOrders(parseInt($("#groupId").val()));
                }
                else
                {
                    swal("Błąd", "Wystąpił bład w momencie zapisu danych zamówień grupy.", {
                        icon: "error"
                    });
                }

            });
        }
    }
});

function GroupLoadOrders(groupId) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.get(`https://admin.lsvrp.ga/ajax/group/get-orders/${groupId}`, function(data) {
       groupShowVue.orders = data;
    });
}