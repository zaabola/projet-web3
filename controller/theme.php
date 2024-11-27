<?php
include_once(__DIR__ . '/../config.php'); // Including the database connection
include_once(__DIR__ . '/../model/themem.php'); // Including the Theme model

class ThemeController
{
    // Function to list all theme
    public function listtheme()
    {
        $sql = "SELECT * FROM theme";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Function to add a new theme
    public function addTheme($theme)
{
    $sql = "INSERT INTO theme (titre, description, image) VALUES (:titre, :description, :image)";
    $db = config::getConnexion();
    
    try {
        $query = $db->prepare($sql);
        
        // Debug the values being passed to the query
        echo "Inserting: ";
        echo "Title: " . $theme->gettitre() . ", Description: " . $theme->getdescription() . ", Image: " . $theme->getimage();

        $query->execute([
            'titre' => $theme->gettitre(),
            'description' => $theme->getdescription(),
            'image' => $theme->getimage()
        ]);
        
        echo "Theme added successfully!";
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}

    


    // Function to delete a theme
    public function deleteTheme($id)
    {
        $sql = "DELETE FROM theme WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        try {
            $query->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Function to update a theme
    public function updateTheme($theme, $id)
    {
        $sql = "UPDATE theme SET titre = :titre, description = :description, image = :image WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $theme->gettitre(),
                'description' => $theme->getDescription(),
                'image' => $theme->getImage(),
                'id' => $id  // Add the 'id' to the execute parameters
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

    // Function to get a theme by ID
    public function getThemeById($id)
    {
        $sql = "SELECT * FROM theme WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        try {
            $query->execute();
            $theme = $query->fetch();
            return $theme;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>
