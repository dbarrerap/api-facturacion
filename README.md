# API-Facturacion

Esta es el backend para un sistema de Facturacion Electronica para Ecuador.
El objetivo de esta API es que sea totalmente agnostico y se pueda crear un frontend en cualquier lenguaje o framework.

## Requisitos
* PHP 8.0
* Java 8.0 (para emitir el archivo RIDE)

## Modelos
Los princiales modelos implemetados son:

* Contribuyente
* Establecimiento
* Punto de Emision
* Proveedor
* Producto
* Cliente
* Factura y FacturaDetalle

Tambien tiene un modelo __Catalogo__ el cual es usado para listas y otros datos que puedan ser necesarios en la aplicacion.

## Rutas
Todas las rutas estan registradas en en namespace _api_.

__POST api/clientes/index__ devuelve el listado de clientes. Recibe 3 parametros: `filter`, `perPage` y `page`. Estos ultimos dos son opcionales.
* `filter`: Es un array asociativo (objeto) el cual puede recibir las siguientes propiedades: `contribuyente_id`, `razon_social` (opcional), `numero_documento` (opcional).
* `perPage`: (Opcional) Es una propiedad numerica que activa la paginacion en la respuesta y haciendo que devuelva la cantidad especificada de registros.
* `page`: (Opcional) Trabaja en conjunto con perPage. Devuelve la pagina especifica de registros. Por default es 1.

__POST api/clientes/store__ almacena un nuevo cliente. Recibe 2 parametros: `data` y `contribuyente`.
* `data`: Es un array asociativo (objeto) con las siguientes propiedades del Cliente: `tipo_documento`, `numero_documento`, `razon_social`, `direccion` (opcional), `telefono`, `email`, `fecha_nacimiento` (opcional).
* `contribuyente`: Es un array asociativo (objeto) con los datos del Contribuyente.

__GET api/clientes/show/{id}__ devuelve los datos de un Cliente especificado por el parametro `id`.

__PUT api/clientes/update/{id}__ actualiza los datos de un Cliente especificado por el parametro `id`. Los parametros actualizables del Cliente son `razon_social`, `direccion`, `telefono`, `email`, `fecha_nacimiento`.

__DELETE api/clientes/destroy/{id}__ elimina un Cliente especificado por el parametro `id`.