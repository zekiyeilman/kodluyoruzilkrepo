package kodluyoruz;

import java.util.Scanner;

public class kdvTutari {

	public static void main(String[] args) {
		/* Eğer girilen tutar 0 ve 1000 TL arasında ise KDV oranı %18 ,
		tutar 1000 TL'den büyük ise KDV oranını %8 olarak KDV tutarı
		hesaplayan programı yazınız. */
        
		double tutar,oran,KDVliTutar,KDVmiktarı;
		Scanner input= new Scanner(System.in);
		tutar = input.nextDouble();
		if (tutar>0&&tutar<=1000) {
			oran = 0.18;
			KDVmiktarı = tutar * oran;
			KDVliTutar = tutar + KDVmiktarı;
			System.out.println("oran :" + oran);
			System.out.println("KDVmiktarı :" + KDVmiktarı );
			System.out.println("KDVliTutar :" + KDVliTutar );
		}
		else{
			oran = 0.08;
			KDVmiktarı = tutar * oran;
			KDVliTutar = tutar + KDVmiktarı;
			System.out.println("oran :" + oran);
			System.out.println("KDVmiktarı :" + KDVmiktarı );
			System.out.println("KDVliTutar :" + KDVliTutar );
		}
	}

}
