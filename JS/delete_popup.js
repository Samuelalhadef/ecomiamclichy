let profileIdToDelete = null;

function openDeleteModal(id) {
    profileIdToDelete = id;
    document.getElementById('deleteModal').style.display = 'block';
    document.getElementById('confirmDelete').onclick = function() {
        window.location.href = '../../Mairie/Users/PHP_UsersDelete.php?id=' + profileIdToDelete;
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Fermer le modal si l'utilisateur clique en dehors
window.onclick = function(event) {
    if (event.target == document.getElementById('deleteModal')) {
        closeDeleteModal();
    }
}