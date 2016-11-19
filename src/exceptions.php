<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Panels;


class IOException extends \RuntimeException
{

}


class FileNotFoundException extends IOException
{

}


class InvalidStateException extends \RuntimeException
{

}


class DuplicateEntryException extends \RuntimeException
{

}


class InvalidArgumentException extends \InvalidArgumentException
{

}


class NotRegisteredException extends \LogicException
{

}
