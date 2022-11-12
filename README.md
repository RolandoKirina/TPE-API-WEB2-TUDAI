# API REST para el recurso de reseñas de chocolates.
Una sencilla API REST de reseñas de chocolates.
# Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera similar) database/tpe.sql
# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews
# Obtener todas las reseñas:
 Verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews.

Si la solicitud sale bien, el "status code" será 200 OK. 
# Obtener una reseña:
Verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id de reseña

Si la solicitud sale bien, el "status code" será 200 OK. 

# Solo ordenar descendentemente por id:
Verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews?order=desc

Si la solicitud sale bien, el "status code" será 200 OK. 

# Crear una nueva reseña :
 verbo http POST + http://localhost/tucarpetalocal/chocolate-rest/api/reviews + http body
Ejemplo de body para el post:
{
  "review": "string",
  "score": 1,
  "id_item": 2
}
Si la solicitud sale bien, el "status code" será 201 CREATED. 

# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id

 Si la solicitud sale bien, el "status code" será 200 OK. 
# Solo Filtrar por puntuacion (numero entre el 1 y 5):
 verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews?filter=enteroentreelunoycinco

  Si la solicitud sale bien, el "status code" será 200 OK. 

# Solo Paginar:
 verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews?page=numero&limit=numero

  Si la solicitud sale bien, el "status code" será 200 OK. 
  
# Filtrar, paginar, ordenar :
  verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?filter=numeroentreelunoycinco&sortby=campo&order=elquequieras&page=numero&limit=loquequieras

  Si la solicitud sale bien, el "status code" será 200 OK. 
  
# Filtrar y ordenar :
  verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?filter=enteroentreelunoycinco&sortby=campo&order=elquequieras

  Si la solicitud sale bien, el "status code" será 200 OK. 
  
# Filtrar y paginar :
  verbo http GET +http://localhost/projects/chocolate_rest/api/reviews?filter=enteroentreelunoycinco&page=numero&limit=numero

  Si la solicitud sale bien, el "status code" será 200 OK. 
  
# Ordenar y paginar : 
 verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?sortby=campo&order=asc/desc&page=numero&limit=numero

  Si la solicitud sale bien, el "status code" será 200 OK. 
  

 # Errores posibles:

 404 Not Found: Este error puede salir, si el recurso al que quiere acceder no existe. Por ejemplo, si pone pagina numero 800 en la paginacion, como no existen tantos datos en la tabla es probable que salga.
 
 400 Bad Request: Este error puede salir, debido a que el servidor no pudo interpretar la solicitud dada una sintaxis inválida en la url.




