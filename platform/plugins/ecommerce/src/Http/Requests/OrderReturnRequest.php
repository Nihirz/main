<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Ecommerce\Enums\OrderReturnReasonEnum;
use Botble\Support\Http\Requests\Request;

class OrderReturnRequest extends Request
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'order_id'                     => 'required|integer|exists:ec_orders,id|unique:ec_order_returns,order_id',
            'reason'                       => 'required|string|in:' . implode(',', OrderReturnReasonEnum::values()),
            'return_items'                 => 'required|array',
            'return_items.*.is_return'     => 'sometimes',
            'return_items.*.order_item_id' => 'required_with:return_items.*.is_return,checked|numeric|exists:ec_order_product,id',
            'return_items.*.qty'           => 'nullable|numeric|min:1',
            'return_items.*.reason'        => 'nullable|string|in:' . implode(',', OrderReturnReasonEnum::values()),
        ];
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'order_id'                     => trans('plugins/ecommerce::order.order_id'),
            'reason'                       => trans('plugins/ecommerce::order.refund_reason'),
            'return_items.*.order_item_id' => trans('plugins/ecommerce::order.product_id'),
            'return_items.*.reason'        => trans('plugins/ecommerce::order.refund_reason'),
            'return_items.*.qty'           => trans('plugins/ecommerce::products.quantity'),
            'return_items.*.is_return'     => trans('plugins/ecommerce::order.is_return'),
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'unique' => __('plugins/ecommerce::order.return_order_unique'),
        ]); // TODO: Change the autogenerated stub
    }
}
