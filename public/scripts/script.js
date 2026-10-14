document.addEventListener('DOMContentLoaded', ()=>{
    console.log("JavaScript loaded!")
});


// 1. Definimos las funciones callback
function saludar(nombre) {
    console.log(`Hola, ${nombre}`);
}

function despedir(nombre) {
    console.log(`Adiós, ${nombre}`);
}

// 2. Función principal (de orden superior) que recibe el callback
function procesarUsuario(nombre, callback) {
    console.log("Procesando usuario...");
    callback(nombre); // Se ejecuta la función pasada como argumento
}

// 3. Invocamos pasando diferentes callbacks
procesarUsuario("Ana", saludar);
// Salida:
// Procesando usuario...
// Hola, Ana

procesarUsuario("Carlos", despedir);
// Salida:
// Procesando usuario...
// Adiós, Carlos



// ========================================================================================

// El almacenamiento global de React
let estados = [];
let indiceActual = 0;

function miUseState(valorInicial) {
  // Guardamos el índice específico asignado a este hook en este renderizado
  const indiceFijo = indiceActual;

  // Si es el primer renderizado, inicializamos el valor
  if (estados[indiceFijo] === undefined) {
    // SOPORTE DE CALLBACK INICIAL: si el valor inicial es una función, la ejecutamos
    if (typeof valorInicial === 'function') {
      estados[indiceFijo] = valorInicial();
    } else {
      estados[indiceFijo] = valorInicial;
    }
  }

  // Función para actualizar el estado
  function setEstado(nuevoValor) {
    // SOPORTE DE CALLBACK DE ACTUALIZACIÓN:
    // Si el usuario pasa una función, le enviamos el estado anterior (prev)
    if (typeof nuevoValor === 'function') {
      estados[indiceFijo] = nuevoValor(estados[indiceFijo]);
    } else {
      estados[indiceFijo] = nuevoValor;
    }

    // Simulamos el comportamiento de React: reiniciar el índice y volver a renderizar
    console.log("...Simulando Re-renderizado...");
    indiceActual = 0;
    renderizarComponente();
  }

  // Avanzamos el índice global para el siguiente hook que se ejecute en el componente
  indiceActual++;

  return [estados[indiceFijo], setEstado];
}



function ComponenteEjemplo() {
  // 1. Inicialización con callback costoso (solo corre la primera vez)
  const [usuario, setUsuario] = miUseState(() => {
    console.log("Calculando estado inicial complejo...");
    return "Ana";
  });

  // 2. Estado normal con número
  const [contador, setContador] = miUseState(0);

  console.log(`[Render] Usuario: ${usuario}, Contador: ${contador}`);

  // Retornamos las acciones para simular clicks del usuario
  return {
    clickCambiarNombre: () => setUsuario("Carlos"),
    clickIncrementarFuncional: () => {
      // Usamos el callback funcional para asegurar el valor previo
      setContador(prev => prev + 1);
      setContador(prev => prev + 1);
    }
  };
}

// --- SIMULACIÓN DEL ENTORNO ---
function renderizarComponente() {
  // Ejecutamos el componente (esto devuelve las funciones de click simuladas)
  const acciones = ComponenteEjemplo();
  return acciones;
}

// --- EJECUCIÓN PASO A PASO ---

console.log("--- PRIMER RENDER ---");
let app = renderizarComponente();
// Salida:
// Calculando estado inicial complejo...
// [Render] Usuario: Ana, Contador: 0

console.log("\n--- ACCIÓN: INCREMENTAR DOS VECES CON CALLBACK ---");
app.clickIncrementarFuncional();
// Salida:
// ...Simulando Re-renderizado...
// [Render] Usuario: Ana, Contador: 2
// (Nota que NO se volvió a ejecutar el callback del estado inicial de 'usuario')
