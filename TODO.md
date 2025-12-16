# TODO: Fix Checkout Button Issue

## Steps to Complete
- [ ] Modify resources/views/order/index.blade.php to use global cart state instead of local cart state
- [ ] Remove local cart initialization and localStorage handling from posSystem() function
- [ ] Update addToCart, removeFromCart, and other cart methods to use global cart
- [ ] Ensure checkout() and submitOrder() use the global cart
- [ ] Test the checkout functionality to confirm it works
