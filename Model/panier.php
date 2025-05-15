<?php 
class PanierItems {
    private $id_panier = null;
    private $total_cost = null;

    public function __construct($total_cost) {
        $this->total_cost = $total_cost;
    }
    
    public function getID() {
        return $this->id_panier;
    }

    public function getTotalCost() {
        return $this->total_cost;
    }

    public function setTotalCost($total_cost) {
        $this->total_cost = $total_cost;
    }
}
?>
