# API REST para el recurso de  reseñas de chocolates.
Una sencilla API REST de reseñas de chocolates.
# Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera) database/tpe.sql
# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews
# Obtener todas las reseñas:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews.
# Obtener una reseña:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id de reseña
# Solo ordenar descendentemente por id:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews?order=desc
# Crear una nueva reseña:
 verbo http POST + http://localhost/tucarpetalocal/chocolate-rest/api/reviews
# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id
# Solo Filtrar por puntuacion:
 http://localhost/tucarpetalocal/chocolate-rest/api/reviews?filter=algodepuntuacion
# Solo Paginar:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews?page=numero&limit=numero
# filtrar, paginar, ordenar :
http://localhost/projects/chocolate_rest/api/reviews?filter=numero&sortby=campo&order=elquequieras&page=numero&limit=loquequieras
# filtrar y ordenar :
http://localhost/projects/chocolate_rest/api/reviews?filter=numerodepuntuacion&sortby=campo&order=elquequieras
# filtrar y paginar :
http://localhost/projects/chocolate_rest/api/reviews?filter=numerodepuntuacion&page=numero&limit=numero
# ordenar y paginar : 
http://localhost/projects/chocolate_rest/api/reviews?sortby=campo&order=asc/desc&page=numero&limit=numero





