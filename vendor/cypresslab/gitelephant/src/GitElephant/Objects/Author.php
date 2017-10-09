<?php
/**
 * GitElephant - An abstraction layer for git written in PHP
 * Copyright (C) 2013  Matteo Giachino
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see [http://www.gnu.org/licenses/].
 */

namespace GitElephant\Objects;

/**
 * Represent a git author
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */
class Author
{
    /**
     * Author name
     *
     * @var string
     */
    private $name;

    /**
     * Author email
     *
     * @var string
     */
    private $email;

    /**
     * return author as RFC 822 representation ( Foo Bar <foo@example.com )
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . ' <' . $this->email . '>';
    }

    /**
     * email setter
     *
     * @param string $email the email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * email getter
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * name setter
     *
     * @param string $name the author name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * name getter
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
