<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once __DIR__ . '/vendor/autoload.php';
use App\Repositories\HelpRequestRepository;
use App\Repositories\TagRepository;

$requestRepo = new HelpRequestRepository();
$tagRepo = new TagRepository();
$tags = $tagRepo->getAllTags();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $apprenantId = $_SESSION['user_id'];
    $tagId = (int) $_POST['tag_id'];

    $requestRepo->createRequest($title, $description, $apprenantId, $tagId);

    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_request'])) {
    $requestId = (int) $_POST['request_id'];

    $tuteurId = $_SESSION['user_id'];

    $requestRepo->acceptRequest($requestId, $tuteurId);

    header("Location: index.php");
    exit;
}

$pendingRequests = $requestRepo->getPendingRequests();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PeerSync - Entraide ENAA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans p-8">

<div class="max-w-5xl mx-auto">

    <header class="flex justify-between items-center mb-10">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-bold text-blue-600">PeerSync ENAA</h1>
            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">Mode MVP</span>
        </div>

        <div class="flex items-center gap-4">
        <span class="text-sm font-medium text-gray-700">
            👋 Salut, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </span>
            <a href="logout.php" class="text-sm bg-red-50 text-red-600 hover:bg-red-100 font-semibold px-4 py-2 rounded-lg transition duration-200">
                Déconnexion
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h2 class="text-xl font-bold mb-4">Demander de l'aide</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la demande</label>
                    <input type="text" name="title" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ex: Problème avec PDO">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" required rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Explique ton blocage..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Compétence concernée</label>
                    <select name="tag_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Sélectionner une technologie...</option>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?php echo $tag['id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="submit_request" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    Publier la demande
                </button>
            </form>
        </div>

        <div class="col-span-1 md:col-span-2">
            <h2 class="text-xl font-bold mb-4">Demandes en attente</h2>

            <div class="space-y-4">
                <?php if (empty($pendingRequests)): ?>
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
                        Aucune demande d'aide pour le moment. Tout le monde va bien !
                    </div>
                <?php else: ?>
                    <?php foreach ($pendingRequests as $request): ?>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-200 flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($request['title']); ?></h3>
                                <p class="text-gray-600 mt-2 text-sm"><?php echo htmlspecialchars($request['description']); ?></p>
                                <div class="mt-4 flex items-center gap-3 text-xs text-gray-500">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">En attente</span>
                                    <span>Créé le : <?php echo date('d/m/Y H:i', strtotime($request['created_at'])); ?></span>
                                </div>
                            </div>

                            <form method="POST" action="">
                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                <button type="submit" name="accept_request" class="bg-green-100 text-green-700 hover:bg-green-200 font-semibold py-2 px-4 rounded-lg text-sm transition duration-200">
                                    Aider
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>