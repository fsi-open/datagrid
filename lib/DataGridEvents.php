<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Component\DataGrid;

final class DataGridEvents
{
    public const PRE_SET_DATA = 'datagrid.pre_set_data';

    public const POST_SET_DATA = 'datagrid.post_set_data';

    public const PRE_BIND_DATA = 'datagrid.pre_bind_data';

    public const POST_BIND_DATA = 'datagrid.post_bind_data';

    public const PRE_BUILD_VIEW = 'datagrid.pre_build_view';

    public const POST_BUILD_VIEW = 'datagrid.post_build_view';
}
