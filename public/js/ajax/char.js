var charShowVue = new Vue({
    el: "#char",
    data: {
        charData: null,
        penaltiesData: null,
        constCharId: null,
        loaded: false,

        fCharId: null,
        fName: null,
        fLastName: null,
        fSex: null,
        fMoney: null,

        banData: { len: 0, desc: "" },
        blockData: { desc: "" }
    },
    methods: {
        kickPlayer: function() {
            "use strict";
            if (this.constCharId === null || this.charData === null) return;
            if (this.charData.InGame === 0) {
                swal("Wystąpił błąd", "Gracz nie jest na serwerze.", {
                    icon: "error"
                })
                return;
            }
        },
        banPlayer: function () {
            "use strict";
            swal({
                title: "Banowanie gracza",
                text: "Wprowadź powód bana",
                content: "input",
                button: {
                    text: "Wybierz dlugość",
                    closeModal: false
                }
            }).then(desc => {
                if (!desc) throw null;
                this.banData.desc = desc;

                swal({
                    title: "Banowanie gracza",
                    text: "Wprowadź długość bana w dniach",
                    content: "input",
                    button: {
                        text: "Przejdź do podsumowania",
                        closeModal: false
                    }
                }).then(len => {
                    if (!len || parseInt(len) === Number.NaN) throw null;
                    this.banData.len = parseInt(len);
                    swal({
                        title: "Podsumowanie bana",
                        text: `Powód bana: ${this.banData.desc}\nDługość bana: ${this.banData.len} dni\nCzy chcesz zbanować użytkownika?`,
                        buttons: {
                            cancel: true,
                            confirm: {
                                text: "Zbanuj",
                                closeModal: false
                            }
                        },
                        dangerMode: true
                    })
                });
            });
        },
        blockChar: function() {
            "use strict";
            if (this.charData === null || this.charData.Blocked === 1)
            {
                swal("Błąd", "Postać jest już zablokowana.", {
                    icon: "error"
                });
                return;
            }
            swal({
                title: "Blokowanie postaci gracza",
                text: "Wprowadź powód blokady",
                content: "input",
                button: {
                    text: "Podsumowanie",
                    closeModal: false
                }
            }).then(desc => {
                if (!desc) throw null;
                this.blockData.desc = desc;
                swal({
                    title: "Podsumowanie blokady postaci",
                    text: `Powód blokady: ${this.blockData.desc}\n\nCzy chcesz zablokować postać użytkownika?`,
                    buttons: {
                        cancel: true,
                        confirm: {
                            text: "Zablokuj",
                            closeModal: false
                        }
                    },
                    dangerMode: true
                }).then(ok => {

                    if (!ok) return;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post("https://admin.lsvrp.ga/ajax/char/penalty", {
                        penaltyType: 1, // blokada postaci
                        penaltyDesc: this.blockData.desc,
                        charId: this.constCharId,
                    }, function(data) {
                        if (data.status === false)
                        {
                            swal("Błąd", "Nie udało się zablokować postaci.", {
                                icon: "error"
                            })
                        }
                        else if (data.status === true)
                        {
                            swal("Sukces", "Postać została zablokowana.", {
                                icon: "success"
                            });
                            LoadCharData(charShowVue.constCharId);
                        }
                    });
                });
            });
        },
        unblockChar: function() {
            "use strict";
            swal({
                title: "Potwierdzenie",
                text: `Czy chcesz odblokować postać graczowi?`,
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Odblokuj",
                        closeModal: false
                    }
                },
                dangerMode: true
            }).then(ok => {

                if (!ok) return;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post("https://admin.lsvrp.ga/ajax/char/penalty", {
                    penaltyType: 2, // anulowanie blokady postaci
                    charId: this.constCharId,
                }, function(data) {
                    if (data.status === false)
                    {
                        swal("Błąd", "Nie udało się odblokować postaci.", {
                            icon: "error"
                        })
                    }
                    else if (data.status === true)
                    {
                        swal("Sukces", "Postać została odblokowana.", {
                            icon: "success"
                        });
                        LoadCharData(charShowVue.constCharId);
                    }
                });
            });
        }
    }
});

function LoadCharData(charId) {
    "use strict";
    $.get(`https://admin.lsvrp.ga/ajax/char/get-data/${charId}`, function(data) {
       if (data.charData.Id) {
            charShowVue.constCharId = data.charData.Id;
            charShowVue.fCharId = data.charData.Id;
            charShowVue.fName = data.charData.Name;
            charShowVue.fLastName = data.charData.Lastname;
            charShowVue.fSex = data.charData.Sex;
            charShowVue.fMoney = data.charData.Cash;

            charShowVue.charData = data.charData;
            charShowVue.penaltiesData = data.charPenalties;

            charShowVue.loaded = true;
       }
    });
}