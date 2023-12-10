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

## Contribuyente
__POST api/contribuyentes/store__ Permite el registro de un Contribuyente y la asignacion de un usuario en el sistema. Recibe 1 parametro: `contribuyente`, el cual es un array asociativo (objeto) con las siguientes propiedades.
* `razon_social`: Nombre completo de la Persona Natural o Razon Social de la Persona Juridica
* `correo`: El correo principal del Contribuyente
* `tipo_documento`: El tipo de documento del Contribuyente. Usualmente es el RUC, pero puede ser Cedula en casos especiales.
* `numero_documento`: El numero del documento de identificacion.
* `direccion`: La direccion registrada en el SRI del Contribuyente.
* `telefono`: Telefono fijo del Contribuyente.
* `movil`: Telefono movil del Contribuyente.
* `contribuyente_especial`: Especifica si el Contribuyente es Contribuyente Especial. Valores posibles: SI, NO.
* `tipo_ambiente`: Especifica el Tipo de Ambiente a usar del SRI. Por defecto es 1, Ambiente de Pruebas. Cambiar a 2 para ambiente de Produccion.
* `obligado_contabilidad`: Especifica si el Contribuyente es obligado a llevar contabilidad segun el SRI. Valores posibles: SI, NO.
* `certificado`: Archivo anexo que es el Certificado para firmar los Documentos Electronicos.
* `clave_certificado`: La clave del Certificado anexado.

__ATENCION__: Las rutas a continuacion usan autenticacion, por lo que el contribuyente debe haber iniciado sesion previamente.

## Establecimiento
__POST api/contribuyentes/index__

__POST api/contribuyentes/store__

__GET api/contribuyentes/show/{id}__

__PUT api/contribuyentes/update/{id}__

__DELETE api/contribuyentes/delete/{id}__

## Punto de Emision
__POST api/puntosemision/index__

__POST api/puntosemision/store__

__GET api/puntosemision/show/{id}__

__PUT api/puntosemision/update/{id}__

__DELETE api/puntosemision/delete/{id}__

## Empleado
__POST api/empleados/index__

__POST api/empleados/store__

__GET api/empleados/show/{id}__

__PUT api/empleados/update/{id}__

__DELETE api/empleados/delete/{id}__

## Cliente
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

## Proveedor
__POST api/proveedores/index__

__POST api/proveedores/store__

__GET api/proveedores/show/{id}__

__PUT api/proveedores/update/{id}__

__DELETE api/proveedores/delete/{id}__

## Producto
__POST api/productos/index__

__POST api/productos/store__

__GET api/productos/show/{id}__

__PUT api/productos/update/{id}__

__DELETE api/productos/delete/{id}__

## Factura
__POST api/facturas/index__

__POST api/facturas/store__

__GET api/facturas/show/{id}__

__PUT api/facturas/update/{id}__

__DELETE api/facturas/delete/{id}__
