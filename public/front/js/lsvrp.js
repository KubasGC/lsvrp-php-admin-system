jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                                                $(window).scrollLeft()) + "px");
    return this;
}

$(document).ready(function() {
  $("#lsvrp_dialog").center();
});

/*var lsvrpModalVue = new Vue({
  el: '#lsvrp_dialog',
  data: {
    header: "Lista pojazdów",
    columns: [{"size":"50","name":"UID"},{"size":"45","name":"Nazwa użytkownika"}],
    datas: [["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"],["10","Kubas"]],
    buttons: ["Spawn", "Opcje"],
    activeRow: null
  }
});
*/

var lsvrpModalVue = new Vue({
  el: '#lsvrp_dialog',
  data: {
    header: "Sklep Sandy Shores",
    columns: [{"size":"70","name":"Nazwa"},{"size":"25","name":"Cena"}],
    datas: [["1","Baton \"Tarcza\"","$200"],["2","Baton \"Tarcza\"","$200"],["3","Baton \"Tarcza\"","$200"],["4","Baton \"Tarcza\"","$200"],["5","Baton \"Kubas\"","$2000"]],
    buttons: ["Kup", "Anuluj"],
    activeRow: null,
    show: true
  },
  methods: {
    firstButton: function() {
      if (this.activeRow === null) return;
      alert(this.datas[this.activeRow][0]);
      this.show = false;
    },
    secondButton: function() {
      this.show = false;
    }
  }
});
