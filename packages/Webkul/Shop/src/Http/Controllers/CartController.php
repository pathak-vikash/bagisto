<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Product\ProductImage;
use Webkul\Product\Product\View as ProductView;
use Cart;

/**
 * Cart controller for the customer
 * and guest users for adding and
 * removing the products in the
 * cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{

    /**
     * Protected Variables that
     * holds instances of the
     * repository classes.
     *
     * @param Array $_config
     * @param $cart
     * @param $cartItem
     * @param $customer
     * @param $product
     * @param $productImage
     * @param $productView
     */
    protected $_config;

    protected $cart;

    protected $cartItem;

    protected $customer;

    protected $product;

    protected $productView;

    public function __construct(
        CartRepository $cart,
        CartItemRepository $cartItem,
        CustomerRepository $customer,
        ProductRepository $product,
        ProductImage $productImage,
        ProductView $productView
    ) {

        // $this->middleware('customer')->except(['add', 'remove', 'test']);

        $this->customer = $customer;

        $this->cart = $cart;

        $this->cartItem = $cartItem;

        $this->product = $product;

        $this->productImage = $productImage;

        $this->productView = $productView;

        $this->_config = request('_config');
    }

    /**
     * Method to populate
     * the cart page which
     * will be populated
     * before the checkout
     * process.
     *
     * @return Mixed
     */
    public function index() {
        // dd(Cart::getCart());
        return view($this->_config['view'])->with('cart', Cart::getCart());
    }

    /**
     * Function for guests
     * user to add the product
     * in the cart.
     *
     * @return Mixed
     */

    public function add($id) {
        $data = request()->input();

        Cart::add($id, $data);

        return redirect()->back();
    }

    /**
     * Removes the item from
     * the cart if it exists
     *
     * @param integer $itemId
     */
    public function remove($itemId) {
        Cart::removeItem($itemId);

        return redirect()->back();
    }

    /**
     * Updates the quantity of the
     *  items present in the cart.
     *
     * @return response
     */
    public function updateBeforeCheckout() {
        $data = request()->except('_token');

        Cart::update($data);

        return redirect()->back();
    }

    public function test() {
    }
}