<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div id="app" class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center">Ticket Details</h2>

                <table class="table table-bordered">
                    <tbody v-if="ticket">
                        <tr><th>Ticket</th><td>@{{ ticket.ticket }}</td></tr>
                        <tr><th>Description</th><td>@{{ ticket.description }}</td></tr>
                        <tr><th>Status</th><td>@{{ ticket.status }}</td></tr>
                        <tr><th>Created On</th><td>@{{ ticket.created_on }}</td></tr>
                    </tbody>
                    <tbody v-else>
                        <tr><td colspan="2" class="text-center text-danger">Loading ticket details...</td></tr>
                    </tbody>
                </table>

                <a class="btn btn-secondary" href="/home">Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="/js/details.js"></script>
</body>
</html>
