<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Entity;


use Nette\Utils\Strings;

trait Name
{


	/**
	 * @Column(type="string", length=255, nullable=true)
	 * @var string|NULL
	 */
	private $name;


	public function getName(): ?string
	{
		return $this->name;
	}


	public function setName(string $name)
	{
		$this->name = Strings::firstUpper($name);
	}


}
