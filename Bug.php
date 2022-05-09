<?php
require_once "/autoload.php";
/**
 * A repository of Bugs
 * 
 * List of bugs that are currently loaded from the persistence.
 * If the synchronized flag is set, then it represents all the available bugs in the persistence.
 * //I need to add feature that prevents loading undisclosable bug into BugRepository.
 */
class BugRepository
{
    /**
     * List of bugs
     * @var array $bugs List of bugs available in the repository.
     */
    private array $bugs;
    /**
     * A synchronization flag to sync between memory and persistence
     * @var bool $synchronized a flag to represent the synchronization between the persistence and memory.
     */
    private bool $synchronized;
    public function __construct($predefinedRepository = array())
    {
        $this->bugs = $predefinedRepository;
        $this->synchronized = false;
    }
    /**
     * Fetch the bug by ID
     * 
     * A method that allows to fetch bug using the unique identifier assigned to that bug.
     * 
     * @param int $id identifier that uniquely identifies the bug.
     */
    public function getBugById(int $id): ?Bug
    {
        if (!isset($this->bugs[$id])){
            $conn = DB::getInstance();
            $query = "SELECT * FROM Bugs WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $bug = $stmt->fetch();
            if (empty($bug)){
                return null;
            }
            $this->bugs[$id] = $bug;
        }
        return $this->bugs[$id];
    }
    /**
     * Fetch all the bugs from the repository
     * 
     * A method that allows us to fetch all the bugs that are present in the repository and persistence
     * 
     * @return Bugs List of bugs
     */
    public function getAllBugs(): Bugs
    {
        if(!$this->synchronized)
        {
            $conn = DB::getInstance();
            $query = "SELECT * FROM Bugs";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            // foreach($rows as $row){
            //     $this->bugs[] = $row;
            // }
            $this->bugs = $rows;
            $this->synchronized = true;
        }
        return $this->bugs;
    }
    /**
     * Insertion of bug
     * 
     * Method that allows us to insert the newly created bug to the bug repository and persistence.
     * 
     * @since 1.0.0
     * 
     * @param string $title Title of the bug
     * @param string $description Description of the bug
     * @param int $status Status of the bug (pending | open | closed | trash)
     * @param bool $discolsable Flag that represents whether bug should be listed in the public domain.
     * @return void
     */
    public function insertBug(string $title, string $description, int $status, bool $disclosable)
    {
        $conn = DB::getInstance();
        $query = "INSERT INTO Bugs (title, description, status, disclosable) VALUES(:title, :description, :status, :disclosable)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":disclosable", $disclosable);
        $stmt->execute();
        //I don't know how to get the last inserted id or row
    }
    public function updateBug(string $title, string $description, int $status, bool $disclosable) 
    {
        //I need to work on this
    }
    public function deleteBugByTitle(string $title)
    {
        try{
            $query = "DELETE FROM Bugs WHERE title=:title";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":title", $title);
            $stmt->execute();
            $this->bugs = array_filter($this->bugs, function($bug){
                if($bug["title"] != $title){
                    return true;
                }
            });
        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

?>