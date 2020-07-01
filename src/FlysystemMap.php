<?php

declare(strict_types=1);

/*
 * This file is part of BiuradPHP opensource projects.
 *
 * PHP version 7.1 and above required
 *
 * @author    Divine Niiquaye Ibok <divineibok@gmail.com>
 * @copyright 2019 Biurad Group (https://biurad.com/)
 * @license   https://opensource.org/licenses/BSD-3-Clause License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BiuradPHP\FileManager;

use ArrayIterator;
use BiuradPHP\FileManager\Interfaces\FlysystemInterface;
use BiuradPHP\FileManager\Interfaces\FlysystemMapInterface;
use InvalidArgumentException;
use IteratorAggregate;

/**
 * Associates filesystem instances to their names.
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class FlysystemMap implements FlysystemMapInterface, IteratorAggregate
{
    /** @var array */
    private $filesystems = [];

    /**
     * {@inheritdoc}
     *
     * @return iterable of all the registered filesystems where the key is the
     *                  name and the value the filesystem
     */
    public function getIterator()
    {
        return new ArrayIterator($this->filesystems);
    }

    /**
     * Register the given filesystem for the specified name.
     *
     * @param string              $name
     * @param FlysystemInterface $filesystem
     *
     * @throws InvalidArgumentException when the specified name contains
     *                                  forbidden characters
     */
    public function set($name, FlysystemInterface $filesystem): void
    {
        if (!\preg_match('/^[-_a-zA-Z0-9]+$/', $name)) {
            throw new InvalidArgumentException(\sprintf('The specified name "%s" is not valid.', $name));
        }

        $this->filesystems[$name] = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->filesystems[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException(\sprintf('There is no filesystem defined having "%s" name.', $name));
        }

        return $this->filesystems[$name];
    }

    /**
     * Removes the filesystem registered for the specified name.
     *
     * @param string $name
     */
    public function remove($name): void
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException(\sprintf('Cannot remove the "%s" filesystem not defined.', $name));
        }

        unset($this->filesystems[$name]);
    }

    /**
     * Clears all the registered filesystems.
     */
    public function clear(): void
    {
        $this->filesystems = [];
    }
}