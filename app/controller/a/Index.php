<?php
declare (strict_types = 1);

namespace app\controller\a;

use app\model\Line as LineModel;
use think\facade\Request;

class Index extends BaseController
{
    public function testDb()
    {

        $data = "{\"Response\":{\"MixedInvoiceItems\":[{\"Code\":\"OK\",\"Type\":-1,\"SubType\":\"OtherInvoice\",\"TypeDescription\":\"其他发票\",\"SubTypeDescription\":\"其他发票\",\"Polygon\":{\"LeftBottom\":{\"X\":24,\"Y\":973},\"LeftTop\":{\"X\":21,\"Y\":0},\"RightBottom\":{\"X\":1535,\"Y\":970},\"RightTop\":{\"X\":1535,\"Y\":0}},\"Angle\":0.039074834,\"SingleInvoiceInfos\":{\"AirTransport\":null,\"BankSlip\":null,\"BusInvoice\":null,\"CustomsDeclaration\":null,\"CustomsPaymentReceipt\":null,\"ElectronicFlightTicketFull\":null,\"ElectronicTrainTicketFull\":null,\"MachinePrintedInvoice\":null,\"MedicalHospitalizedInvoice\":null,\"MedicalOutpatientInvoice\":null,\"MotorVehicleSaleInvoice\":null,\"MotorVehicleSaleInvoiceElectronic\":null,\"NonTaxIncomeElectronicBill\":null,\"NonTaxIncomeGeneralBill\":null,\"OnlineTaxiItinerary\":null,\"OtherInvoice\":{\"Date\":\"\",\"OtherInvoiceListItems\":[{\"Name\":\"发票名称\",\"Value\":\"买家信息\"},{\"Name\":\"订单编号\",\"Value\":\"4804665408556353329\"},{\"Name\":\"昵称\",\"Value\":\"宛**\"},{\"Name\":\"收货地址\",\"Value\":\"杨丹,15784004702-7918,江苏省南京市江宁区横溪街道吴楚西路美的雍翠园物业前台,000000\"},{\"Name\":\"支付宝交易号\",\"Value\":\"2025101322001118421418293173\"},{\"Name\":\"联系电话\",\"Value\":\"151******53\"},{\"Name\":\"邮件\",\"Value\":\"b***\"},{\"Name\":\"创建时间\",\"Value\":\"2025-10-13 00:20:50\"},{\"Name\":\"支付宝\",\"Value\":\"1***付款给买家\"},{\"Name\":\"运送方式\",\"Value\":\"快递\"},{\"Name\":\"付款时间\",\"Value\":\"2025-10-13 00:20:52\"},{\"Name\":\"数量/单价\",\"Value\":\"21.80x1\"},{\"Name\":\"状态\",\"Value\":\"未发货\"},{\"Name\":\"发货时间\",\"Value\":\"10月15日00:20前发货\"},{\"Name\":\"用户实付\",\"Value\":\"¥21.80\"},{\"Name\":\"商家实收\",\"Value\":\"¥0.00\"},{\"Name\":\"包含快递\",\"Value\":\"¥0.00\"}],\"OtherInvoiceTableItems\":[],\"Title\":\"订单信息\",\"Total\":\"￥0.00\"},\"OverseasInvoice\":null,\"QuotaInvoice\":null,\"SaleInventory\":null,\"ShippingInvoice\":null,\"ShoppingReceipt\":null,\"TaxPayment\":null,\"TaxiTicket\":null,\"TollInvoice\":null,\"TrainTicket\":null,\"UsedCarPurchaseInvoice\":null,\"UsedCarPurchaseInvoiceElectronic\":null,\"VatCommonInvoice\":null,\"VatElectronicCommonInvoice\":null,\"VatElectronicInvoiceBlockchain\":null,\"VatElectronicInvoiceFull\":null,\"VatElectronicInvoiceToll\":null,\"VatElectronicSpecialInvoice\":null,\"VatElectronicSpecialInvoiceFull\":null,\"VatInvoiceRoll\":null,\"VatSalesList\":null,\"VatSpecialInvoice\":null},\"Page\":1,\"CutImage\":\"\",\"ItemPolygon\":[],\"QRCode\":\"\",\"InvoiceSealInfo\":{\"CompanySealMark\":\"0\",\"SupervisionSealMark\":\"0\",\"CompanySealMarkInfo\":[],\"SupervisionSealMarkInfo\":[]}}],\"RequestId\":\"5d327d1a-b92a-4ae9-9bd1-f83dcaa01468\",\"TotalPDFCount\":1}}";

        $data = json_decode($data, true);
        if ($data['Response']['TotalPDFCount'] == 1) {
            $data  = $data['Response']['MixedInvoiceItems'][0]['SingleInvoiceInfos']['OtherInvoice']['OtherInvoiceListItems'];
            $data2 = [
                'sn'            => '',
                'order_date'    => '',
                'order_price'   => 0,
                'order_user'    => '',
                'order_zone'    => [],
                'order_address' => '',
            ];

            foreach ($data as $item) {
                switch ($item['Name']) {
                    case '订单编号':
                        $data2['sn'] = $item['Value'];
                        break;
                    case '收货地址':
                        $data2['order_address'] = $item['Value'];
                        break;
                    case '创建时间':
                        $data2['order_date'] = $item['Value'];
                        break;
                    case '用户实付':
                        $data2['order_price'] = $item['Value'];
                        break;
                    default:
                        break;
                }
            }
            if ($data2['order_address']) {
                $res = explode(',', $data2['order_address']);
                if ($res[1]) {
                    $res2 = explode('-', $res[1]);
                }
                $data2['order_user'] = $res[0] . '[' . $res2[1] . ']';
            }
            if ($data2['order_price']) {
                $data2['order_price'] = str_replace('¥', '', $data2['order_price']);
            }
            dump($data2);
        } else {
            dump('errrrrrrrrrrrr');
        }

    }

    public function testGet()
    {
        // $postData = Request::post();
        // return $this->success(' testGet ', $postData);
        // $s = 'abc程序';
        // echo "原始代码:[$s]\n";
        // $s = base64_encode($s);
        // echo "编码后:[$s]\n";
        // $s = base64_decode($s);
        // echo "解码后:[$s]\n";

        dump('aaa');
    }

    public function testPost()
    {
        //$postData = Request::post();
        $where = $this->request->getMore([
            ['time', ''],
            ['type', '222'],
        ]);
        return $this->success($where);
    }

    public function test()
    {
        $list = LineModel::where(1)->limit(0, 10)->select()->toArray();
        dump($list);
    }

}
