<?php
//Model
class CurrencyConverter {
    private $baseValue = 0;
    private $rates = [
        'GBP' => 1.0,
        'USD' => 0.6,
        'EUR' => 0.83,
        'YEN' => 0.0058
    ];
    public function get($currency) {
        if (isset($this->rates[$currency])) {
            $rate = 1/$this->rates[$currency];
            return round($this->baseValue * $rate, 2);
        }
        else return 0;
    }
    public function set($amount, $currency = 'GBP') {
        if (isset($this->rates[$currency])) {
            $this->baseValue = $amount * $this->rates[$currency];
        }
    }
}
//View
class CurrencyConverterView {
    private $converter;
    private $currency;
    public function __construct(CurrencyConverter $converter, $currency) {
        $this->converter = $converter;
        $this->currency = $currency;
    }
    public function output() {
        $html = '<form action="?action=convert" method="post"><input name="currency" type="hidden" value="' . $this->currency .'"/><label>' . $this->currency .':</label><input name="amount" type="text" value="' . $this->converter->get($this->currency) . '"/><input type="submit" value="Convert"/></form>';
        return $html;
    }
}
//Controller
class CurrencyConverterController {
    private $currencyConverter;
    public function __construct(CurrencyConverter $currencyConverter) {
        $this->currencyConverter = $currencyConverter;
    }
    public function convert($request) {
        if (isset($request['currency']) && isset($request['amount'])) {
            $this->currencyConverter->set($request['amount'], $request['currency']);
        }
    }
}
//Application initialisation/entry point. In Java, this would be the static main method.
$model = new CurrencyConverter();
$controller = new CurrencyConverterController($model);
//Check for presence of $_GET['action'] to see if a controller action is required
if (isset($_GET['action'])) $controller->{$_GET['action']}($_POST);
$gbpView = new CurrencyConverterView($model, 'GBP');
echo $gbpView->output();
$usdView = new CurrencyConverterView($model, 'USD');
echo $usdView->output();
$eurView = new CurrencyConverterView($model, 'EUR');
echo $eurView->output();
$yenView = new CurrencyConverterView($model, 'YEN');
echo $yenView->output();
/*
$currencyConverter = new CurrencyConverter;
$currencyConverter->set(100, 'USD');
echo '100 USD is: ';
echo $currencyConverter->get('GBP') . ' GBP / ';
echo $currencyConverter->get('EUR') . ' EUR / ';
echo $currencyConverter->get('YEN') . ' YEN';
//
$currencyConverter = new CurrencyConverter;
$currencyConverter->set(100, 'GBP');
echo '100 GBP is:';
echo $currencyConverter->get('USD') . ' USD / ';
echo $currencyConverter->get('EUR') . ' EUR / ';
echo $currencyConverter->get('YEN') . ' YEN';
$currencyConverter = new CurrencyConverter;
*/