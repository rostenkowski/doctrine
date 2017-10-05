<?php declare(strict_types=1);

namespace Rostenkowski\Doctrine\Entity;


trait Id
{

	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue(strategy="AUTO")
	 * @var int
	 */
	protected $id;


	public function getId(): ?int
	{
		return $this->id;
	}

}
