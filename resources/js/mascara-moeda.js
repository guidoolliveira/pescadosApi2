const salarioInput = document.getElementById('salario');

salarioInput.addEventListener('input', function (e) {
    let valor = e.target.value.replace(/\D/g, '');

    if (valor.length < 3) valor = valor.padStart(3, '0');

    let intPart = valor.slice(0, -2).replace(/^0+/, '') || '0'; // remove zeros à esquerda
    let decimalPart = valor.slice(-2);
    let formatted = `${intPart},${decimalPart}`;

    // Adiciona pontos como separadores de milhar
    formatted = formatted.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    e.target.value = formatted;
});

