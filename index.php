<?php
include_once './model/database.php';
include_once './model/person.php';

$database = new Database();
$conn = $database->getConnection();

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$persons = Person::getAll($conn, $offset, $limit);

$total_persons = $conn->query("SELECT COUNT(*) AS count FROM persons")->fetch_assoc()['count'];
$total_pages = ceil($total_persons / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Person List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Person List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Street</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Postal Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($persons as $person): ?>
                <tr>
                    <td><?php echo $person->getId(); ?></td>
                    <td><?php echo $person->getName(); ?></td>
                    <td><?php echo $person->getAge(); ?></td>
                    <td><?php echo $person->getAddress()->getStreet(); ?></td>
                    <td><?php echo $person->getAddress()->getCity(); ?></td>
                    <td><?php echo $person->getAddress()->getState(); ?></td>
                    <td><?php echo $person->getAddress()->getPostalCode(); ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="editPerson(<?php echo $person->getId(); ?>)">Update</button>
                        <a href="delete.php?id=<?php echo $person->getId(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item"><a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Update Person</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="update.php" method="post">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">Name:</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-age">Age:</label>
                            <input type="number" class="form-control" id="edit-age" name="age" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-street">Street:</label>
                            <input type="text" class="form-control" id="edit-street" name="street" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-city">City:</label>
                            <input type="text" class="form-control" id="edit-city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-state">State:</label>
                            <input type="text" class="form-control" id="edit-state" name="state" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-postalCode">Postal Code:</label>
                            <input type="text" class="form-control" id="edit-postalCode" name="postalCode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function editPerson(id) {
            $.ajax({
                url: './model/get_person.php',
                type: 'GET',
                data: { id: id },
                success: function(response) {
                    var person = JSON.parse(response);
                    $('#edit-id').val(person.id);
                    $('#edit-name').val(person.name);
                    $('#edit-age').val(person.age);
                    $('#edit-street').val(person.street);
                    $('#edit-city').val(person.city);
                    $('#edit-state').val(person.state);
                    $('#edit-postalCode').val(person.postalCode);
                    $('#editModal').modal('show');
                }
            });
        }
    </script>
</body>
</html>