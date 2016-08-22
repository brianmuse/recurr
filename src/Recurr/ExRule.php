<?php

/*
 * Copyright 2013 Shaun Simmons
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Based on rrule.js
 * Copyright 2010, Jakub Roztocil and Lars Schoning
 * https://github.com/jkbr/rrule/blob/master/LICENCE
 *
 * Based on python-dateutil - Extensions to the standard Python datetime module.
 * Copyright (c) 2003-2011 - Gustavo Niemeyer <gustavo@niemeyer.net>
 * Copyright (c) 2012 - Tomi Pieviläinen <tomi.pievilainen@iki.fi>
 */

namespace Recurr;

/**
 */
class ExRule extends Rule
{
    /**
     * @var string
     */
    const NAME = 'EXRULE';

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }
}
