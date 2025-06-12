$(document).ready(function(){
    $('#telefone').inputmask('(99) 99999-9999');
});

function formatSalario(input) {
    let valor = input.value.replace(/[^\d,\.]/g, '');
    valor = valor.replace(/\./g, ',');
    input.value = valor;
}

