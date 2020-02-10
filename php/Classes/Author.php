<?php

namespace pleyba4\ObjectOriented;

require_once ("autoload.php");
require_once (dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Author implements \JsonSerializable {
	//use ValidateDate;
	use ValidateUuid;

	/**
	 * id for this Author; this is the primary key
	 * @var Uuid $authorId
	 **/
	private $authorId;
	/**
	 * Activation Token for this Author
	 * @var string $authorActivationToken
	 **/
	private $authorActivationToken;
	/**
	 * Url for Avatar pic of Author
	 * @var $authorAvatarUrl
	 **/
	private $authorAvatarUrl;
	/**
	 * Author's Email  not null, unique(authorEmail),
	 * @var $authorEmail
	 **/
	private $authorEmail;
	/**
	 * Author's Hash
	 *Hash char(97) not null,
	 * @var $authorHash
	 **/
	private $authorHash;
	/**
	 * Author's Username
	 *Hash varchar(32) not null, unique(authorUsername)
	 * @var $authorUsername
	 **/
	private $authorUsername;

	/**
	 * constructor for this Author
	 *
	 * @param string|Uuid $newAuthorIdid of this Tweet or null if a new Tweet
	 * @param string $newAuthorAvatarUrl string containing url.
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	public function __construct($newAuthorId, $newAuthorActivationToken, string $newAuthorAvatarUrl, $newAuthorEmail, $newAuthorHash, $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUsername($newAuthorUsername);

		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * mutator method for author id
	 *
	 * @param Uuid|string $newAuthorId new value of Author id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if $newAuthorId is not a uuid or string
	 **/

	public function setAuthorId($newAuthorId) : void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the authorid
		$this->authorId = $uuid;


		//$this -> authorId = $newAuthorId
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 */
	public function setAuthorActivationToken(?string $newAuthorActivationToken): void{
		if($newAuthorActivationToken === null){
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false){
			throw(new\RangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 32){
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this -> authorActivationToken = $newAuthorActivationToken;
	}
	public function setAuthorAvatarUrl($newAuthorAvatarUrl){
		$this -> authorActivationToken = $newAuthorAvatarUrl;
	}
	/**
	 * mutator method for email
	 *
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/

	public function setAuthorEmail(string $newAuthorEmail) : void {
		// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 97) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this -> authorEmail = $newAuthorEmail;
	}
	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 */
	public function setAuthorHash(string $newAuthorHash): void{
		//enforce that hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce the hash is an Argon hash
		$authorHashInfo = password_get_info($newAuthorHash);
		if($authorHashInfo["algoName"] !== "argon2i") {
			throw(new \InvalidArgumentException("profile hash is not a valid hash"));
		}
		//enforce that the hash is exactly 96 characters.
		if(strlen($newAuthorHash)!==96) {
			throw(new\RangeException("profile hash must be 96 characters"));
		}
		//store the hash
		$this -> authorHash = $newAuthorHash;
	}
	public function setAuthorUsername($newAuthorUsername){
		$this -> authorUsername = $newAuthorUsername;
	}

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id
	 **/

	public function getAuthorId(): Uuid {
		return $this->authorId;
	}
	public function getAuthorActivationToken(): ?string {
		return $this-> authorActivationToken;
	}
	public function getAuthorAvatarUrl(){
		return $this-> authorAvatarUrl;
	}
	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getAuthorEmail(): string {
		return $this-> authorEmail;
	}

	/**
	 * accessor method for author Hash
	 *
	 * @return string value of hash
	 */
	public function getAuthorHash() : string {
		return $this-> authorHash;
	}
	public function getAuthorUsername(){
		return $this-> authorUsername;
	}

	public function jsonSerialize() : array{
		$fields = get_object_vars($this);
		$fields["authorId"] = $this->authorId->toString();
		return($fields);
	}
	// TODO: Implement jsonSerialize() method.}

	/**
	 * insert author into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when my SQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		//insert query
		$query = "INSERT INTO author(authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername) VALUES(:authorId, :authorActivationToken, :authorAvatarUrl, :authorEmail, :authorHash, :authorUsername)";
		$statement = $pdo->prepare($query);
	}

	/**
	 * update this author in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {

		//update query
		$query = "UPDATE author SET authorId = :authorId, authorActivaitonToken = :authorActivationToken, authorAvatarUrl = :authorAvatarUrl, authorEmail = :authorEmail, authorHash = :authorHash, authorUsername = :authorUsername";
		$statement = $pdo->prepare($query);
	}
	/**
	 * delete author from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {
		$query = "DELETE FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);

	}

	/**
	 * gets the author by authorId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $authorId tweet id to search for
	 * @return Author|null Author found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getAuthorbyAuthorId(\PDO $pdo, $authorId) : ?Author {
		// sanitize the authorId before searching
		try {
			$authorId = self::validateUuid($authorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT authorId, authorActivationToken, authorAvatarUrl, authorEmail, authorHash, authorUsername FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);

		// bind the author id to the place holder in the template
		$parameters = ["authorId" => $authorId->getBytes()];
		$statement->execute($parameters);

		// grab the author from mySQL
		try {
			$author = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$author = new Author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"], $row["authorEmail"], $row["authorHash"], $row["authorUsername"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($author);
	}


	/**
	 * gets the author by author id and returns an array
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $authorId author id to search by
	 * @return \SplFixedArray SplFixedArray of Tweets found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAuthorsByAuthorId(\PDO $pdo, $authorId): \SplFixedArray {

		try {
			$authorId = self::validateUuid($authorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//query template
		$query = "SELECT authorId, authorActivaitonToken, authorAvatarUrl, authorEmail, authorHash, authorUsername FROM author WHERE authorId = :authorId";
		$statement = $pdo->prepare($query);
		// bind the author id to the place holder in the template
		$parameters = ["authorId" => $authorId->getBytes()];
		$statement->execute($parameters);
		// build an array of authors
		$authors = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$authors = new author($row["authorId"], $row["authorActivationToken"], $row["authorAvatarUrl"], $row["authorEmail"], $row["authorHash"], $row["authorUsername"]);
				$authors[$authors->key()] = $authors;
				$authors->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($authors);
	}

}
